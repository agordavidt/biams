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
    public function index()
    {
        $applications = ResourceApplication::with(['user', 'resource'])
            ->latest()
            ->paginate(10);

        return view('admin.applications.index', compact('applications'));
    }

    public function show(ResourceApplication $application)
    {
        $application->load(['user', 'resource']);
        return view('admin.applications.show', compact('application'));
    }

    public function updateStatus(Request $request, ResourceApplication $application)
    {
        $validated = $request->validate([
            'status' => 'required|in:' . implode(',', ResourceApplication::getStatusOptions()),
            'note' => 'nullable|string|max:500'
        ]);

        if (!$application->canTransitionTo($validated['status'])) {
            return back()->with('error', 'Invalid status transition.');
        }

        $application->updateStatus($validated['status']);
        
        // Send notification to user
        $application->user->notify(new ApplicationStatusUpdated($application, $validated['note'] ?? null));

        return back()->with('success', 'Application status updated successfully.');
    }

    public function filterApplications(Request $request)
    {
        $query = ResourceApplication::with(['user', 'resource']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            })->orWhereHas('resource', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        $applications = $query->latest()->paginate(10);
        
        if ($request->ajax()) {
            return view('admin.applications.partials.applications-table', compact('applications'));
        }

        return view('admin.applications.index', compact('applications'));
    }
}