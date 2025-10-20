<?php

namespace App\Http\Controllers\LGAAdmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCooperativeRequest;
use App\Http\Requests\UpdateCooperativeRequest;
use App\Models\Cooperative;
use App\Models\Farmer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class CooperativeController extends Controller
{
    protected $cacheTTL = 300;

    /**
     * Display a listing of cooperatives in the LGA
     */
    public function index(Request $request)
    {
        $lgaId = auth()->user()->administrative_id;
        
        $query = Cooperative::where('lga_id', $lgaId)
            ->with(['registeredBy:id,name'])
            ->withCount('members');

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('registration_number', 'like', "%{$search}%")
                  ->orWhere('contact_person', 'like', "%{$search}%");
            });
        }

        // Activity filter
        if ($request->filled('activity')) {
            $query->whereJsonContains('primary_activities', $request->activity);
        }

        $cooperatives = $query->orderBy('created_at', 'desc')->paginate(15);

        // Statistics
        $stats = Cache::remember("lga_{$lgaId}_cooperative_stats", $this->cacheTTL, function () use ($lgaId) {
            return [
                'total_cooperatives' => Cooperative::where('lga_id', $lgaId)->count(),
                'total_members' => Cooperative::where('lga_id', $lgaId)->sum('total_member_count'),
                'total_land_managed' => Cooperative::where('lga_id', $lgaId)->sum('total_land_size'),
                'active_this_month' => Cooperative::where('lga_id', $lgaId)
                    ->where('created_at', '>=', now()->startOfMonth())
                    ->count(),
            ];
        });

        return view('lga_admin.cooperatives.index', compact('cooperatives', 'stats'));
    }

    /**
     * Show the form for creating a new cooperative
     */
    public function create()
    {
        $activities = $this->getActivityOptions();
        
        return view('lga_admin.cooperatives.create', compact('activities'));
    }

    /**
     * Store a newly created cooperative
     */
    public function store(StoreCooperativeRequest $request)
    {
        try {
            DB::beginTransaction();

            $lgaId = auth()->user()->administrative_id;

            // Check for duplicate registration number
            if (Cooperative::where('registration_number', $request->registration_number)->exists()) {
                return back()
                    ->withInput()
                    ->with('error', 'A cooperative with this registration number already exists.');
            }

            $cooperative = Cooperative::create([
                'registration_number' => $request->registration_number,
                'name' => $request->name,
                'contact_person' => $request->contact_person,
                'phone' => $request->phone,
                'email' => $request->email,
                'total_member_count' => $request->total_member_count ?? 0,
                'total_land_size' => $request->total_land_size,
                'primary_activities' => $request->primary_activities,
                'lga_id' => $lgaId,
                'registered_by' => auth()->id(),
            ]);

            DB::commit();

            Log::info('Cooperative registered successfully', [
                'cooperative_id' => $cooperative->id,
                'registered_by' => auth()->id(),
                'lga_id' => $lgaId,
            ]);

            // Clear cache
            Cache::forget("lga_{$lgaId}_cooperative_stats");

            return redirect()
                ->route('lga_admin.cooperatives.show', $cooperative)
                ->with('success', 'Cooperative registered successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Cooperative registration failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()
                ->withInput()
                ->with('error', 'Error registering cooperative: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified cooperative
     */
    public function show(Cooperative $cooperative)
    {
        $this->authorizeCooperativeAccess($cooperative);

        $cooperative->load([
            'lga:id,name',
            'registeredBy:id,name,email',
            'members' => function($query) {
                $query->select('farmers.id', 'farmers.full_name', 'farmers.phone_primary', 'farmers.status')
                    ->withPivot('membership_number', 'joined_date', 'membership_status', 'position')
                    ->orderBy('cooperative_farmer.joined_date', 'desc');
            }
        ]);

        // Member statistics
        $memberStats = [
            'active_members' => $cooperative->members()->wherePivot('membership_status', 'active')->count(),
            'inactive_members' => $cooperative->members()->wherePivot('membership_status', 'inactive')->count(),
            'total_enrolled' => $cooperative->members()->count(),
        ];

        return view('lga_admin.cooperatives.show', compact('cooperative', 'memberStats'));
    }

    /**
     * Show the form for editing the cooperative
     */
    public function edit(Cooperative $cooperative)
    {
        $this->authorizeCooperativeAccess($cooperative);

        $activities = $this->getActivityOptions();

        return view('lga_admin.cooperatives.edit', compact('cooperative', 'activities'));
    }

    /**
     * Update the specified cooperative
     */
    public function update(UpdateCooperativeRequest $request, Cooperative $cooperative)
    {
        $this->authorizeCooperativeAccess($cooperative);

        try {
            DB::beginTransaction();

            // Check for duplicate registration number (excluding current cooperative)
            if (Cooperative::where('registration_number', $request->registration_number)
                ->where('id', '!=', $cooperative->id)
                ->exists()) {
                return back()
                    ->withInput()
                    ->with('error', 'A cooperative with this registration number already exists.');
            }

            $cooperative->update([
                'registration_number' => $request->registration_number,
                'name' => $request->name,
                'contact_person' => $request->contact_person,
                'phone' => $request->phone,
                'email' => $request->email,
                'total_member_count' => $request->total_member_count ?? 0,
                'total_land_size' => $request->total_land_size,
                'primary_activities' => $request->primary_activities,
            ]);

            DB::commit();

            Log::info('Cooperative updated successfully', [
                'cooperative_id' => $cooperative->id,
                'updated_by' => auth()->id(),
            ]);

            // Clear cache
            Cache::forget("lga_{$cooperative->lga_id}_cooperative_stats");

            return redirect()
                ->route('lga_admin.cooperatives.show', $cooperative)
                ->with('success', 'Cooperative updated successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Cooperative update failed', [
                'cooperative_id' => $cooperative->id,
                'error' => $e->getMessage()
            ]);

            return back()
                ->withInput()
                ->with('error', 'Error updating cooperative: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified cooperative
     */
    public function destroy(Cooperative $cooperative)
    {
        $this->authorizeCooperativeAccess($cooperative);

        // Check if cooperative has members
        if ($cooperative->members()->exists()) {
            return back()->with('error', 'Cannot delete a cooperative with registered members. Remove all members first.');
        }

        try {
            $lgaId = $cooperative->lga_id;
            $cooperative->delete();

            Log::info('Cooperative deleted', [
                'cooperative_id' => $cooperative->id,
                'deleted_by' => auth()->id()
            ]);

            // Clear cache
            Cache::forget("lga_{$lgaId}_cooperative_stats");

            return redirect()
                ->route('lga_admin.cooperatives.index')
                ->with('success', 'Cooperative deleted successfully.');

        } catch (\Exception $e) {
            Log::error('Cooperative deletion failed', [
                'cooperative_id' => $cooperative->id,
                'error' => $e->getMessage()
            ]);

            return back()->with('error', 'Error deleting cooperative: ' . $e->getMessage());
        }
    }

    /**
     * Show members management page
     */
    public function members(Cooperative $cooperative)
    {
        $this->authorizeCooperativeAccess($cooperative);

        $cooperative->load([
            'members' => function($query) {
                $query->select('farmers.id', 'farmers.full_name', 'farmers.phone_primary', 'farmers.email', 'farmers.status')
                    ->withPivot('membership_number', 'joined_date', 'exit_date', 'membership_status', 'position', 'notes')
                    ->orderBy('cooperative_farmer.joined_date', 'desc');
            }
        ]);

        // Get available farmers from this LGA who are not members
        $availableFarmers = Farmer::where('lga_id', $cooperative->lga_id)
            ->where('status', 'active')
            ->whereDoesntHave('cooperatives', function($query) use ($cooperative) {
                $query->where('cooperative_id', $cooperative->id);
            })
            ->orderBy('full_name')
            ->get(['id', 'full_name', 'phone_primary', 'ward']);

        return view('lga_admin.cooperatives.members', compact('cooperative', 'availableFarmers'));
    }

    /**
     * Add a member to the cooperative
     */
    public function addMember(Request $request, Cooperative $cooperative)
    {
        $this->authorizeCooperativeAccess($cooperative);

        $request->validate([
            'farmer_id' => ['required', 'exists:farmers,id'],
            'membership_number' => ['required', 'string', 'max:50'],
            'joined_date' => ['required', 'date', 'before_or_equal:today'],
            'position' => ['nullable', 'string', 'max:100'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        try {
            // Check if farmer is from the same LGA
            $farmer = Farmer::findOrFail($request->farmer_id);
            if ($farmer->lga_id !== $cooperative->lga_id) {
                return back()->with('error', 'Farmer must be from the same LGA as the cooperative.');
            }

            // Check if already a member (using 'cooperatives' relationship)
            if ($farmer->cooperatives()->where('cooperative_id', $cooperative->id)->exists()) {
                return back()->with('error', 'This farmer is already a member of the cooperative.');
            }

            $cooperative->members()->attach($farmer->id, [
                'membership_number' => $request->membership_number,
                'joined_date' => $request->joined_date,
                'membership_status' => 'active',
                'position' => $request->position,
                'notes' => $request->notes,
            ]);

            Log::info('Member added to cooperative', [
                'cooperative_id' => $cooperative->id,
                'farmer_id' => $farmer->id,
                'added_by' => auth()->id()
            ]);

            return back()->with('success', "Member {$farmer->full_name} added successfully!");

        } catch (\Exception $e) {
            Log::error('Failed to add member to cooperative', [
                'error' => $e->getMessage()
            ]);

            return back()->with('error', 'Error adding member: ' . $e->getMessage());
        }
    }

    /**
     * Remove a member from the cooperative
     */
    public function removeMember(Cooperative $cooperative, Farmer $farmer)
    {
        $this->authorizeCooperativeAccess($cooperative);

        try {
            $cooperative->members()->updateExistingPivot($farmer->id, [
                'membership_status' => 'inactive',
                'exit_date' => now(),
            ]);

            Log::info('Member removed from cooperative', [
                'cooperative_id' => $cooperative->id,
                'farmer_id' => $farmer->id,
                'removed_by' => auth()->id()
            ]);

            return back()->with('success', "Member {$farmer->full_name} removed successfully!");

        } catch (\Exception $e) {
            Log::error('Failed to remove member from cooperative', [
                'error' => $e->getMessage()
            ]);

            return back()->with('error', 'Error removing member: ' . $e->getMessage());
        }
    }

    /**
     * Export cooperatives data
     */
    public function export()
    {
        // Implementation for Excel export would go here
        // Using Laravel Excel package
        return back()->with('info', 'Export functionality coming soon.');
    }

    /**
     * Authorize LGA access to cooperative
     */
    private function authorizeCooperativeAccess(Cooperative $cooperative)
    {
        if ($cooperative->lga_id !== auth()->user()->administrative_id) {
            abort(403, 'Unauthorized access to cooperative outside your LGA.');
        }
    }

    /**
     * Get activity options
     */
    private function getActivityOptions(): array
    {
        return [
            'Input Procurement',
            'Output Marketing',
            'Processing',
            'Storage',
            'Transportation',
            'Credit/Finance',
            'Training/Extension',
            'Equipment Sharing',
            'Bulk Purchasing',
            'Market Linkage',
        ];
    }
}