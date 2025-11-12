<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use App\Models\Resource;
use Illuminate\Http\Request;

class VendorController extends Controller
{
    /**
     * Display all vendors with basic stats
     */
    public function index(Request $request)
    {
        $query = Vendor::withCount(['users', 'resources']);

        // Search
        if ($request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('legal_name', 'like', "%$search%")
                  ->orWhere('contact_person_name', 'like', "%$search%")
                  ->orWhere('contact_person_email', 'like', "%$search%");
            });
        }

        // Filter by status
        if ($request->has('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        // Filter by organization type
        if ($request->organization_type) {
            $query->where('organization_type', $request->organization_type);
        }

        $vendors = $query->latest()->paginate(20)->withQueryString();

        // Summary statistics
        $stats = [
            'total' => Vendor::count(),
            'active' => Vendor::where('is_active', true)->count(),
            'inactive' => Vendor::where('is_active', false)->count(),
            'total_resources' => Resource::vendorResources()->count(),
            'active_resources' => Resource::vendorResources()->where('status', 'active')->count(),
        ];

        return view('super_admin.vendors.index', compact('vendors', 'stats'));
    }

    /**
     * Display vendor details
     */
    public function show(Vendor $vendor)
    {
        $vendor->load(['users.roles', 'registeredBy']);

        // Load resources with pagination
        $resources = $vendor->resources()
            ->withCount('applications')
            ->latest()
            ->paginate(15);

        // Vendor statistics
        $stats = [
            'total_users' => $vendor->users()->count(),
            'vendor_managers' => $vendor->vendorManager()->count(),
            'distribution_agents' => $vendor->distributionAgents()->count(),
            'total_resources' => $vendor->resources()->count(),
            'proposed_resources' => $vendor->resources()->where('status', 'proposed')->count(),
            'under_review_resources' => $vendor->resources()->where('status', 'under_review')->count(),
            'active_resources' => $vendor->resources()->where('status', 'active')->count(),
            'rejected_resources' => $vendor->resources()->where('status', 'rejected')->count(),
            'total_applications' => $vendor->resources()->withCount('applications')->get()->sum('applications_count'),
        ];

        return view('super_admin.vendors.show', compact('vendor', 'resources', 'stats'));
    }

    /**
     * Toggle vendor active status
     */
    public function toggleStatus(Vendor $vendor)
    {
        try {
            $vendor->update(['is_active' => !$vendor->is_active]);
            
            $status = $vendor->is_active ? 'activated' : 'deactivated';
            
            return redirect()->back()
                ->with('success', "Vendor {$status} successfully");
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error updating vendor status');
        }
    }

    /**
     * Export vendors to CSV
     */
    public function export(Request $request)
    {
        $vendors = Vendor::query()
            ->withCount(['users', 'resources'])
            ->with('registeredBy')
            ->when($request->status, fn($q, $status) => $q->where('is_active', $status === 'active'))
            ->when($request->organization_type, fn($q, $type) => $q->where('organization_type', $type))
            ->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="vendors-' . date('Y-m-d') . '.csv"',
        ];

        $callback = function() use ($vendors) {
            $file = fopen('php://output', 'w');
            
            fputcsv($file, [
                'ID', 'Legal Name', 'Organization Type', 'Contact Person', 
                'Email', 'Phone', 'Address', 'Status', 'Total Users', 
                'Total Resources', 'Registered By', 'Registered At'
            ]);

            foreach ($vendors as $vendor) {
                fputcsv($file, [
                    $vendor->id,
                    $vendor->legal_name,
                    ucfirst($vendor->organization_type),
                    $vendor->contact_person_name,
                    $vendor->contact_person_email,
                    $vendor->contact_person_phone,
                    $vendor->address,
                    $vendor->is_active ? 'Active' : 'Inactive',
                    $vendor->users_count,
                    $vendor->resources_count,
                    $vendor->registeredBy ? $vendor->registeredBy->name : 'N/A',
                    $vendor->created_at->format('Y-m-d H:i'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}