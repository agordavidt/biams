<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ResourceApplication;
use App\Models\Resource;
use App\Notifications\ResourceStatusUpdated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

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

        // Get statistics for each resource
        $resourceStats = Resource::query()
            ->withCount([
                'applications as total_applications',
                'applications as approved_count' => function($q) {
                    $q->where('status', ResourceApplication::STATUS_APPROVED);
                },
                'applications as rejected_count' => function($q) {
                    $q->where('status', ResourceApplication::STATUS_REJECTED);
                },
                'applications as pending_count' => function($q) {
                    $q->where('status', ResourceApplication::STATUS_PENDING);
                }
            ])
            ->having('total_applications', '>', 0)
            ->get()
            ->keyBy('id');

        return view('admin.resources.applications.index', compact('applications', 'resourceStats'));
    }

    public function show(ResourceApplication $application)
    {
        $application->load(['user', 'resource']);
        $statusOptions = ResourceApplication::getStatusOptions();
        
        // Get statistics for this specific resource
        $resourceStats = ResourceApplication::where('resource_id', $application->resource_id)
            ->selectRaw('
                COUNT(*) as total,
                SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) as approved,
                SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) as rejected,
                SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) as pending
            ', [
                ResourceApplication::STATUS_APPROVED,
                ResourceApplication::STATUS_REJECTED,
                ResourceApplication::STATUS_PENDING
            ])
            ->first();
        
        return view('admin.resources.applications.show', [
            'application' => $application,
            'statusOptions' => $statusOptions,
            'resourceStats' => $resourceStats
        ]);
    }

    public function grant(Request $request, ResourceApplication $application)
    {
        return $this->processStatusUpdate(
            $request, 
            $application, 
            ResourceApplication::STATUS_APPROVED, 
            'Resource application granted successfully.'
        );
    }

    public function decline(Request $request, ResourceApplication $application)
    {
        // Make notes required for decline
        $request->validate([
            'notes' => 'required|string|max:500|min:10'
        ], [
            'notes.required' => 'Please provide a reason for declining this application.',
            'notes.min' => 'Decline reason must be at least 10 characters.'
        ]);

        return $this->processStatusUpdate(
            $request, 
            $application, 
            ResourceApplication::STATUS_REJECTED, 
            'Resource application declined successfully.'
        );
    }

    protected function processStatusUpdate(Request $request, ResourceApplication $application, string $newStatus, string $successMessage)
    {
        // Validate notes
        $validated = $request->validate([
            'notes' => 'nullable|string|max:500'
        ]);

        // Check if status transition is valid
        if (!$application->canTransitionTo($newStatus)) {
            return back()
                ->with('error', 'Invalid status transition. Application must be pending to update status.')
                ->withInput();
        }

        try {
            // Update status
            $application->update([
                'status' => $newStatus,
                'admin_notes' => $validated['notes'] ?? null,
                'processed_at' => now(),
                'processed_by' => auth()->id()
            ]);
            
            // Notify user of status change
            try {
                $application->user->notify(
                    new ResourceStatusUpdated($application, $validated['notes'] ?? null)
                );
            } catch (\Exception $e) {                
                Log::error('Notification failed: ' . $e->getMessage(), [
                    'user_id' => $application->user->id,
                    'application_id' => $application->id,
                    'notification' => ResourceStatusUpdated::class,
                ]);
                // Don't fail the whole operation if notification fails
            }

            return redirect()
                ->route('resources.applications.show', $application)
                ->with('success', $successMessage);
            
        } catch (\Exception $e) {
            Log::error('Status update failed: ' . $e->getMessage(), [
                'application_id' => $application->id,
                'new_status' => $newStatus
            ]);
            
            return back()
                ->with('error', 'Failed to update status. Please try again.')
                ->withInput();
        }
    }
    
    public function bulkUpdate(Request $request)
    {
        $validated = $request->validate([
            'applications' => 'required|array|min:1',
            'applications.*' => 'exists:resource_applications,id', 
            'status' => 'required|in:' . ResourceApplication::STATUS_APPROVED . ',' . ResourceApplication::STATUS_REJECTED,
            'notes' => 'nullable|string|max:500'
        ]);

        $updatedCount = 0;
        $failedCount = 0;
        $applications = ResourceApplication::whereIn('id', $validated['applications'])->get();

        foreach ($applications as $application) {
            if ($application->canTransitionTo($validated['status'])) {
                try {
                    $application->update([
                        'status' => $validated['status'],
                        'admin_notes' => $validated['notes'] ?? null,
                        'processed_at' => now(),
                        'processed_by' => auth()->id()
                    ]);
                    
                    try {
                        $application->user->notify(
                            new ResourceStatusUpdated($application, $validated['notes'] ?? null)
                        );
                    } catch (\Exception $e) {                   
                        Log::error('Bulk notification failed: ' . $e->getMessage(), [
                            'user_id' => $application->user->id,
                            'application_id' => $application->id,
                        ]);
                    }
                    
                    $updatedCount++;
                } catch (\Exception $e) {
                    $failedCount++;
                    Log::error('Bulk update failed for application: ' . $e->getMessage(), [
                        'application_id' => $application->id
                    ]);
                }
            }
        }

        $message = "Successfully updated {$updatedCount} application(s)";
        if ($failedCount > 0) {
            $message .= ". {$failedCount} application(s) could not be updated.";
        }

        return back()->with('success', $message);
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