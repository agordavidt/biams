<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ResourceApplication;
use App\Models\User;
use App\Notifications\ApplicationStatusUpdated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class ResourceApplicationController extends Controller
{
    /**
     * Display a listing of resource applications.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = ResourceApplication::with(['user', 'resource']);

        // Handle status filtering
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Handle search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('user', function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                })->orWhereHas('resource', function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                });
            });
        }

        // Get paginated results
        $applications = $query->latest()->paginate(10);

        return view('admin.resources.applications.index', compact('applications'));
    }

    /**
     * Display the specified resource application.
     *
     * @param ResourceApplication $application
     * @return \Illuminate\View\View
     */
    public function show(ResourceApplication $application)
    {
        $application->load(['user', 'resource']);
        
        return view('admin.resources.applications.show', compact('application'));
    }

    /**
     * Update the status of a resource application.
     *
     * @param Request $request
     * @param ResourceApplication $application
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateStatus(Request $request, ResourceApplication $application)
    {
        $validated = $request->validate([
            'status' => [
                'required',
                'string',
                'in:' . implode(',', ResourceApplication::getStatusOptions())
            ],
            'note' => 'nullable|string|max:500'
        ]);

        // Check if the status transition is valid
        if (!$application->canTransitionTo($validated['status'])) {
            return back()
                ->with('error', 'Invalid status transition.')
                ->withInput();
        }

        // Update the application status
        if ($application->updateStatus($validated['status'])) {
            // Send notification to user
            $application->user->notify(new ApplicationStatusUpdated(
                $application,
                $validated['note'] ?? null
            ));

            return back()->with('success', 'Application status updated successfully.');
        }

        return back()
            ->with('error', 'Failed to update application status.')
            ->withInput();
    }

    /**
     * Export applications data (optional functionality).
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function export(Request $request)
    {
        $query = ResourceApplication::with(['user', 'resource']);

        // Apply filters if any
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $applications = $query->get();

        // Generate CSV
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="applications.csv"',
        ];

        $callback = function() use ($applications) {
            $file = fopen('php://output', 'w');
            
            // Add headers
            fputcsv($file, [
                'ID',
                'User',
                'Email',
                'Resource',
                'Status',
                'Submitted Date',
                'Last Updated'
            ]);

            // Add data
            foreach ($applications as $application) {
                fputcsv($file, [
                    $application->id,
                    $application->user->name,
                    $application->user->email,
                    $application->resource->name,
                    $application->status,
                    $application->created_at->format('Y-m-d H:i:s'),
                    $application->updated_at->format('Y-m-d H:i:s')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Bulk update application statuses (optional functionality).
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function bulkUpdate(Request $request)
    {
        $validated = $request->validate([
            'applications' => 'required|array',
            'applications.*' => 'exists:resource_applications,id',
            'status' => [
                'required',
                'string',
                'in:' . implode(',', ResourceApplication::getStatusOptions())
            ],
            'note' => 'nullable|string|max:500'
        ]);

        $applications = ResourceApplication::whereIn('id', $validated['applications'])->get();
        $updatedCount = 0;

        foreach ($applications as $application) {
            if ($application->canTransitionTo($validated['status'])) {
                if ($application->updateStatus($validated['status'])) {
                    $updatedCount++;
                    
                    // Send notification
                    $application->user->notify(new ApplicationStatusUpdated(
                        $application,
                        $validated['note'] ?? null
                    ));
                }
            }
        }

        return back()->with('success', "{$updatedCount} applications updated successfully.");
    }
}