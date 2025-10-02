<?php

namespace App\Http\Controllers\EnrollmentAgent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Farmer;
use App\Models\LGA;
use App\Models\Cooperative;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class FarmerController extends Controller
{
    /**
     * Display a listing of farmers enrolled by the current agent.
     * Shows all statuses (Pending, Rejected, Active).
     */
    public function index()
    {
        $farmers = Farmer::with('lga')
            ->enrolledBy(auth()->id())
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('enrollment_agent.farmers.index', compact('farmers'));
    }

    /**
     * Show the form for creating a new farmer profile.
     */
    public function create()
    {
        // Pass necessary data for the complex form (e.g., LGAs, occupations, etc.)
        $lgas = LGA::all(); 
        $cooperatives = Cooperative::all(); // Assuming Cooperative model is defined
        return view('enrollment_agent.farmers.create', compact('lgas', 'cooperatives'));
    }

    /**
     * Store a newly created farmer profile.
     */
    public function store(Request $request)
    {
        // NOTE: A dedicated FormRequest is recommended for robust validation (e.g., StoreFarmerRequest)
        $validatedData = $request->validate([
            'nin' => ['required', 'unique:farmers,nin', 'string', 'max:15'],
            'full_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:farmers,email'],
            'phone_primary' => ['required', 'unique:farmers,phone_primary', 'string', 'max:15'],
            // ... (rest of validation rules for Farmer, FarmLand, and Practice Details)
        ]);

        try {
            DB::beginTransaction();

            $farmer = Farmer::create(array_merge($validatedData, [
                'enrolled_by' => auth()->id(),
                'status' => 'pending_lga_review',
                // Auto-generate initial password (handled by the Farmer Model's boot method)
                'initial_password' => Str::random(12), 
            ]));

            // Logic to create FarmLand and associated Practice Details goes here.
            // Example:
            // $farmLand = $farmer->farmLands()->create($request->farm_data);
            // $farmLand->practiceDetails()->create($request->practice_data);
            
            DB::commit();

            return redirect()->route('enrollment_agent.farmers.index')
                ->with('success', 'Farmer profile submitted successfully for LGA review.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error submitting profile: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified farmer profile.
     */
    public function show(Farmer $farmer)
    {
        // EOs can only view profiles they enrolled
        if ($farmer->enrolled_by !== auth()->id()) {
            abort(403);
        }
        
        // Ensure necessary relationships are loaded for the view
        $farmer->load('lga', 'cooperative', 'farmLands.practiceDetails');

        return view('enrollment_agent.farmers.show', compact('farmer'));
    }

    /**
     * Show the form for editing (resubmitting) the specified farmer profile.
     */
    public function edit(Farmer $farmer)
    {
        if ($farmer->enrolled_by !== auth()->id()) {
            abort(403);
        }

        // Only allow editing if the submission is still pending review or has been rejected
        if (in_array($farmer->status, ['active', 'pending_activation'])) {
            return redirect()->route('enrollment_agent.farmers.show', $farmer)
                ->with('error', 'Cannot edit a profile that has been accepted or activated.');
        }

        $farmer->load('farmLands.practiceDetails');
        $lgas = LGA::all();
        $cooperatives = Cooperative::all();

        return view('enrollment_agent.farmers.edit', compact('farmer', 'lgas', 'cooperatives'));
    }

    /**
     * Update/Resubmit the specified farmer profile.
     */
    public function update(Request $request, Farmer $farmer)
    {
        if ($farmer->enrolled_by !== auth()->id()) {
            abort(403);
        }

        // Deny update if already approved
        if (in_array($farmer->status, ['active', 'pending_activation'])) {
            throw ValidationException::withMessages(['status' => 'Profile is accepted and cannot be modified.']);
        }
        
        // Validation logic here (similar to store, but unique checks may need to ignore the current farmer)
        
        try {
            DB::beginTransaction();
            
            // Update Farmer core data
            $farmer->update($request->only(['full_name', 'email', 'phone_primary', /* ... */]));

            // Set status back to pending review, clearing the rejection reason if applicable
            $farmer->status = 'pending_lga_review';
            $farmer->rejection_reason = null;
            $farmer->save();

            // Logic to update/sync FarmLand and Practice Details goes here.

            DB::commit();

            return redirect()->route('enrollment_agent.farmers.index')
                ->with('success', 'Farmer profile updated and resubmitted for LGA review.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error updating profile: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified farmer from storage.
     */
    public function destroy(Farmer $farmer)
    {
        if ($farmer->enrolled_by !== auth()->id()) {
            abort(403);
        }
        
        // Only allow deletion if not approved
        if (in_array($farmer->status, ['active', 'pending_activation'])) {
            return back()->with('error', 'Cannot delete an accepted or activated profile.');
        }

        $farmer->delete();

        return redirect()->route('enrollment_agent.farmers.index')
            ->with('success', 'Farmer profile deleted.');
    }
}