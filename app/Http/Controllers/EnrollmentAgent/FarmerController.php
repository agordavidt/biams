<?php

namespace App\Http\Controllers\EnrollmentAgent;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreFarmerRequest;
use App\Http\Requests\StoreFarmLandRequest;
use App\Models\Farmer;
use App\Models\FarmLand;
use App\Models\CropPracticeDetails;
use App\Models\LivestockPracticeDetails;
use App\Models\FisheriesPracticeDetails;
use App\Models\OrchardPracticeDetails;
use App\Models\LGA;
use App\Models\Cooperative;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class FarmerController extends Controller
{
    /**
     * Display a listing of farmers enrolled by the current agent.
     */
    public function index()
    {
        $farmers = Farmer::with(['lga', 'cooperative', 'farmLands'])
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
        $lgas = LGA::orderBy('name')->get();
        $cooperatives = Cooperative::orderBy('name')->get();
        
        return view('enrollment_agent.farmers.create', compact('lgas', 'cooperatives'));
    }

    /**
     * Store a newly created farmer profile with farm and practice details.
     */
    public function store(StoreFarmerRequest $request)
    {
        try {
            DB::beginTransaction();

            // Step 1: Create Farmer Profile
            $farmerData = $request->only([
                'nin', 'full_name', 'email', 'phone_primary', 'phone_secondary',
                'date_of_birth', 'gender', 'marital_status', 'ward',
                'residential_address', 'residence_latitude', 'residence_longitude',
                'educational_level', 'household_size', 'primary_occupation',
                'other_occupation', 'cooperative_id'
            ]);

            $agentLgaId = auth()->user()->administrativeUnit->id;
            $farmerData['lga_id'] = $agentLgaId;

            $farmerData['enrolled_by'] = auth()->id();
            $farmerData['status'] = 'pending_lga_review';
            $farmerData['initial_password'] = Str::random(12);
            $farmerData['password_changed'] = false;

            $farmer = Farmer::create($farmerData);

            // Step 2: Handle Photo Uploads
            if ($request->hasFile('farmer_photo')) {
                $farmerPhotoPath = $request->file('farmer_photo')->store(
                    'farmers/photos/' . $farmer->id,
                    'public'
                );
                $farmer->update(['farmer_photo' => $farmerPhotoPath]);
            }

            // Step 3: Create Farm Land Entry
            $farmLandData = $request->only([
                'name', 'farm_type', 'total_size_hectares',
                'ownership_status', 'geolocation_geojson'
            ]);

            $farmLand = $farmer->farmLands()->create($farmLandData);

            // Step 4: Handle Farm Photo Upload
            if ($request->hasFile('farm_photo')) {
                $farmPhotoPath = $request->file('farm_photo')->store(
                    'farms/photos/' . $farmLand->id,
                    'public'
                );
                // Store farm photo path in additional_info
                $additionalInfo = $farmer->additional_info ?? [];
                $additionalInfo['farm_photo_path'] = $farmPhotoPath;
                $farmer->update(['additional_info' => $additionalInfo]);
            }

            // Step 5: Create Practice Details Based on Farm Type
            $this->createPracticeDetails($farmLand, $request);

            DB::commit();

            return redirect()
                ->route('enrollment.farmers.index')
                ->with('success', 'Farmer profile submitted successfully for LGA review. Enrollment ID: ' . $farmer->id);

        } catch (\Exception $e) {
            DB::rollBack();
            
            \Log::error('Farmer Enrollment Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return back()
                ->withInput()
                ->with('error', 'Error submitting farmer profile: ' . $e->getMessage());
        }
    }

    /**
     * Create practice-specific details based on farm type
     */
    private function createPracticeDetails(FarmLand $farmLand, Request $request)
    {
        switch ($farmLand->farm_type) {
            case 'crops':
                CropPracticeDetails::create([
                    'farm_land_id' => $farmLand->id,
                    'crop_type' => $request->crop_type,
                    'variety' => $request->variety,
                    'expected_yield_kg' => $request->expected_yield_kg,
                    'farming_method' => $request->farming_method,
                ]);
                break;

            case 'livestock':
                LivestockPracticeDetails::create([
                    'farm_land_id' => $farmLand->id,
                    'animal_type' => $request->animal_type,
                    'herd_flock_size' => $request->herd_flock_size,
                    'breeding_practice' => $request->breeding_practice,
                ]);
                break;

            case 'fisheries':
                FisheriesPracticeDetails::create([
                    'farm_land_id' => $farmLand->id,
                    'fishing_type' => $request->fishing_type,
                    'species_raised' => $request->species_raised,
                    'pond_size_sqm' => $request->pond_size_sqm,
                    'expected_harvest_kg' => $request->expected_harvest_kg,
                ]);
                break;

            case 'orchards':
                OrchardPracticeDetails::create([
                    'farm_land_id' => $farmLand->id,
                    'tree_type' => $request->tree_type,
                    'number_of_trees' => $request->number_of_trees,
                    'maturity_stage' => $request->maturity_stage,
                ]);
                break;
        }
    }

    /**
     * Display the specified farmer profile.
     */
    public function show(Farmer $farmer)
    {
        // Ensure enrollment officer can only view their own enrollments
        if ($farmer->enrolled_by !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }
        
        $farmer->load([
            'lga',
            'cooperative',
            'enrolledBy',
            'approvedBy',
            'farmLands' => function($query) {
                $query->with([
                    'cropPracticeDetails',
                    'livestockPracticeDetails', 
                    'fisheriesPracticeDetails',
                    'orchardPracticeDetails'
                ]);
            }
        ]);

        return view('enrollment_agent.farmers.show', compact('farmer'));
    }

    /**
     * Show the form for adding a new farmland to an existing farmer.
     */
    public function createFarmLand(Farmer $farmer)
    {
        if ($farmer->enrolled_by !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        // Only allow adding farmlands to approved farmers
        if (!in_array($farmer->status, ['pending_activation', 'active'])) {
            return redirect()
                ->route('enrollment.farmers.show', $farmer)
                ->with('error', 'Cannot add farmlands to a profile that is not approved.');
        }

        return view('enrollment_agent.farmers.create-farmland', compact('farmer'));
    }

    /**
     * Store a new farmland for an existing farmer.
     */
    public function storeFarmLand(StoreFarmLandRequest $request, Farmer $farmer)
    {
        if ($farmer->enrolled_by !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        // Only allow adding farmlands to approved farmers
        if (!in_array($farmer->status, ['pending_activation', 'active'])) {
            return back()->with('error', 'Cannot add farmlands to a profile that is not approved.');
        }

        try {
            DB::beginTransaction();

            // Create Farm Land Entry
            $farmLandData = $request->only([
                'name', 'farm_type', 'total_size_hectares',
                'ownership_status', 'geolocation_geojson'
            ]);

            $farmLand = $farmer->farmLands()->create($farmLandData);

            // Create Practice Details Based on Farm Type
            $this->createPracticeDetails($farmLand, $request);

            DB::commit();

            return redirect()
                ->route('enrollment.farmers.show', $farmer)
                ->with('success', 'Farmland added successfully to farmer profile.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            \Log::error('FarmLand Creation Error: ' . $e->getMessage());

            return back()
                ->withInput()
                ->with('error', 'Error adding farmland: ' . $e->getMessage());
        }
    }

    /**
     * Show farmer credentials
     */
    public function showCredentials(Farmer $farmer)
    {
        if ($farmer->enrolled_by !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        if (!in_array($farmer->status, ['pending_activation', 'active'])) {
            return back()->with('error', 'Credentials are only available for approved farmers.');
        }

        return view('enrollment_agent.farmers.credentials', compact('farmer'));
    }

    /**
     * Show the form for editing (resubmitting) the specified farmer profile.
     */
    public function edit(Farmer $farmer)
    {
        if ($farmer->enrolled_by !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        // Only allow editing if pending or rejected
        if (in_array($farmer->status, ['active', 'pending_activation'])) {
            return redirect()
                ->route('enrollment.farmers.show', $farmer)
                ->with('error', 'Cannot edit a profile that has been approved or activated.');
        }

        $farmer->load(['farmLands' => function($query) {
            $query->with([
                'cropPracticeDetails',
                'livestockPracticeDetails', 
                'fisheriesPracticeDetails',
                'orchardPracticeDetails'
            ]);
        }]);
        
        $lgas = LGA::orderBy('name')->get();
        $cooperatives = Cooperative::orderBy('name')->get();

        return view('enrollment_agent.farmers.edit', compact('farmer', 'lgas', 'cooperatives'));
    }

    /**
     * Update/Resubmit the specified farmer profile.
     */
    public function update(StoreFarmerRequest $request, Farmer $farmer)
    {
        if ($farmer->enrolled_by !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        // Prevent update if already approved
        if (in_array($farmer->status, ['active', 'pending_activation'])) {
            return back()->with('error', 'Profile is approved and cannot be modified.');
        }
        
        try {
            DB::beginTransaction();
            
            // Update Farmer Profile
            $farmerData = $request->only([
                'nin', 'full_name', 'email', 'phone_primary', 'phone_secondary',
                'date_of_birth', 'gender', 'marital_status', 'ward',
                'residential_address', 'residence_latitude', 'residence_longitude',
                'educational_level', 'household_size', 'primary_occupation',
                'other_occupation', 'cooperative_id'
            ]);

            $farmer->update($farmerData);

            // Reset status to pending review and clear rejection reason
            $farmer->status = 'pending_lga_review';
            $farmer->rejection_reason = null;
            $farmer->approved_by = null;
            $farmer->approved_at = null;
            $farmer->save();

            // Update photos if new ones uploaded
            if ($request->hasFile('farmer_photo')) {
                if ($farmer->farmer_photo) {
                    Storage::disk('public')->delete($farmer->farmer_photo);
                }
                $farmerPhotoPath = $request->file('farmer_photo')->store(
                    'farmers/photos/' . $farmer->id,
                    'public'
                );
                $farmer->update(['farmer_photo' => $farmerPhotoPath]);
            }

            // Update Farm Land (first farmland only for edits)
            $farmLand = $farmer->farmLands()->first();
            
            if ($farmLand) {
                $farmLandData = $request->only([
                    'name', 'farm_type', 'total_size_hectares',
                    'ownership_status', 'geolocation_geojson'
                ]);
                
                $farmLand->update($farmLandData);

                if ($request->hasFile('farm_photo')) {
                    $additionalInfo = $farmer->additional_info ?? [];
                    if (isset($additionalInfo['farm_photo_path'])) {
                        Storage::disk('public')->delete($additionalInfo['farm_photo_path']);
                    }
                    $farmPhotoPath = $request->file('farm_photo')->store(
                        'farms/photos/' . $farmLand->id,
                        'public'
                    );
                    $additionalInfo['farm_photo_path'] = $farmPhotoPath;
                    $farmer->update(['additional_info' => $additionalInfo]);
                }

                // Delete old practice details and create new ones
                $this->deletePracticeDetails($farmLand);
                $this->createPracticeDetails($farmLand, $request);
            }

            DB::commit();

            return redirect()
                ->route('enrollment.farmers.index')
                ->with('success', 'Farmer profile updated and resubmitted for LGA review.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            \Log::error('Farmer Update Error: ' . $e->getMessage());

            return back()
                ->withInput()
                ->with('error', 'Error updating profile: ' . $e->getMessage());
        }
    }

    /**
     * Delete existing practice details
     */
    private function deletePracticeDetails(FarmLand $farmLand)
    {
        $farmLand->cropPracticeDetails()->delete();
        $farmLand->livestockPracticeDetails()->delete();
        $farmLand->fisheriesPracticeDetails()->delete();
        $farmLand->orchardPracticeDetails()->delete();
    }

    /**
     * Remove the specified farmer from storage.
     */
    public function destroy(Farmer $farmer)
    {
        if ($farmer->enrolled_by !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }
        
        // Only allow deletion if not approved
        if (in_array($farmer->status, ['active', 'pending_activation'])) {
            return back()->with('error', 'Cannot delete an approved or activated profile.');
        }

        try {
            // Delete associated photos
            if ($farmer->farmer_photo) {
                Storage::disk('public')->delete($farmer->farmer_photo);
            }

            $additionalInfo = $farmer->additional_info ?? [];
            if (isset($additionalInfo['farm_photo_path'])) {
                Storage::disk('public')->delete($additionalInfo['farm_photo_path']);
            }

            $farmer->delete();

            return redirect()
                ->route('enrollment.farmers.index')
                ->with('success', 'Farmer profile deleted successfully.');

        } catch (\Exception $e) {
            return back()->with('error', 'Error deleting profile: ' . $e->getMessage());
        }
    }
}