<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ResourceApplication;
use App\Notifications\ResourceStatusUpdated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class ResourceApplicationController extends Controller
{
    public function index(Request $request)
    {
        $applications = ResourceApplication::query()
            ->with(['user', 'resource'])
            ->when($request->status, fn($q, $status) => $q->where('status', $status))
            ->when($request->search, function($q, $search) {
                $q->whereHas('user', fn($q) => $q->where('name', 'like', "%$search%")
                    ->orWhere('email', 'like', "%$search%"))
                ->orWhereHas('resource', fn($q) => $q->where('name', 'like', "%$search%"));
            })
            ->latest()
            ->paginate(15);

        return view('admin.resources.applications.index', compact('applications'));
    }

    public function show(ResourceApplication $application)
    {
        $application->load(['user', 'resource']);
        $statusOptions = ResourceApplication::getStatusOptions();
        
        return view('admin.resources.applications.show', [
            'application' => $application,
            'statusOptions' => $statusOptions
        ]);
    }

    public function updateStatus(Request $request, ResourceApplication $application)
    {
        $validated = $request->validate([
            'status' => 'required|in:' . implode(',', ResourceApplication::getStatusOptions()),
            'notes' => 'nullable|string|max:500'
        ]);

        if (!$application->canTransitionTo($validated['status'])) {
            return back()
                ->with('error', 'Invalid status transition')
                ->withInput();
        }

        try {
            $application->update(['status' => $validated['status']]);
            
            // Notify user of status change
            $application->user->notify(
                new ResourceStatusUpdated($application, $validated['notes'] ?? null)
            );

            return back()->with('success', 'Application status updated');
            
        } catch (\Exception $e) {
            return back()
                ->with('error', 'Failed to update status: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function bulkUpdate(Request $request)
    {
        $validated = $request->validate([
            'applications' => 'required|array',
            'applications.*' => 'exists:resource_applications,id',
            'status' => 'required|in:' . implode(',', ResourceApplication::getStatusOptions()),
            'notes' => 'nullable|string|max:500'
        ]);

        $updatedCount = 0;
        $applications = ResourceApplication::whereIn('id', $validated['applications'])->get();

        foreach ($applications as $application) {
            if ($application->canTransitionTo($validated['status'])) {
                $application->update(['status' => $validated['status']]);
                $application->user->notify(
                    new ResourceStatusUpdated($application, $validated['notes'] ?? null)
                );
                $updatedCount++;
            }
        }

        return back()->with('success', "Updated $updatedCount applications");
    }

    public function export(Request $request)
    {
        $applications = ResourceApplication::query()
            ->with(['user', 'resource'])
            ->when($request->status, fn($q, $status) => $q->where('status', $status))
            ->when($request->date_from, fn($q, $date) => $q->whereDate('created_at', '>=', $date))
            ->when($request->date_to, fn($q, $date) => $q->whereDate('created_at', '<=', $date))
            ->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="resource-applications-' . date('Y-m-d') . '.csv"',
        ];

        $callback = function() use ($applications) {
            $file = fopen('php://output', 'w');
            
            // Headers
            fputcsv($file, [
                'ID', 'User', 'Email', 'Resource', 
                'Status', 'Payment Status', 'Applied At', 'Last Updated'
            ]);

            // Data
            foreach ($applications as $app) {
                fputcsv($file, [
                    $app->id,
                    $app->user->name,
                    $app->user->email,
                    $app->resource->name,
                    ucfirst($app->status),
                    $app->payment_status ? ucfirst($app->payment_status) : 'N/A',
                    $app->created_at->format('Y-m-d H:i'),
                    $app->updated_at->format('Y-m-d H:i')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}