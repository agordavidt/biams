<?php


namespace App\Http\Controllers;

use App\Models\Farmers\CropFarmer;
use App\Models\Farmers\AnimalFarmer;
use App\Models\Farmers\Processor;
use App\Models\Farmers\AbattoirOperator;
use Illuminate\Http\Request;

class FarmersController extends Controller
{
    // Show the form for crop farmers
    public function showCropFarmerForm()
    {
     
        return view('farmers.crop');
    }

    // Store crop farmer data
    public function storeCropFarmer(Request $request)
    {
       // Validate the incoming request
        $request->validate([
            'phone' => 'required|string|min:10|max:15',
            'dob' => 'required|date',
            'gender' => 'required|string',
            'education' => 'required|string',
            'household_size' => 'required|integer',
            'dependents' => 'required|integer',
            'income_level' => 'required|string',
            'lga' => 'required|string',
            'farm_size' => 'required|numeric',
            'farming_methods' => 'required|string',
            'seasonal_pattern' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'crop' => 'required|string',
            'other_crop' => 'nullable|string', // Optional if 'Other' is selected
            'farm_location' => 'required|string',
        ]);

        // Check if 'Other' crop is selected and merge it into 'crop' field
        $crop = $request->crop === 'Other' ? $request->other_crop : $request->crop;

        // Create a new CropFarmer record
        $cropFarmer = CropFarmer::create([
            'user_id' => auth()->id(),
            'phone' => $request->phone,
            'dob' => $request->dob,
            'gender' => $request->gender,
            'education' => $request->education,
            'household_size' => $request->household_size,
            'dependents' => $request->dependents,
            'income_level' => $request->income_level,
            'lga' => $request->lga,
            'farm_size' => $request->farm_size,
            'farming_methods' => $request->farming_methods,
            'seasonal_pattern' => $request->seasonal_pattern,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'farm_location' => $request->farm_location,
            'crop' => $crop,
            'other_crop' => $request->crop === 'Other' ? $request->other_crop : null,
        ]);


       
        // Update user status to "pending"
        auth()->user()->update(['status' => 'pending']);


        return redirect()->route('home')->with('success', 'Crop farmer profile updated successfully!');
    }

    // Show the form for animal farmers
    public function showAnimalFarmerForm()
    {
         

        return view('farmers.animal');
    }

    // Store animal farmer data
    public function storeAnimalFarmer(Request $request)
    {
        $request->validate([
            'phone' => 'required|string|min:10|max:15',
            'dob' => 'required|date',
            'gender' => 'required|in:Male,Female,Other',
            'education' => 'required|string',
            'household_size' => 'required|integer',
            'dependents' => 'required|integer',
            'income_level' => 'required|string',
            'lga' => 'required|string',
            'herd_size' => 'required|integer',
            'facility_type' => 'required|string',
            'breeding_program' => 'required|string',
            'farm_location' => 'required|string',
            'livestock' => 'required|in:Cattle,Goats,Sheep,Poultry,Pigs,Fish,Other',
            'other_livestock' => 'nullable|string',            
        ]);

        // If 'Other' is selected for livestock, merge the value from 'other_livestock'
          $livestock = ($request->livestock === 'Other' && $request->has('other_livestock') && $request->other_livestock) 
            ? $request->other_livestock 
            : $request->livestock;


        // Create AnimalFarmer profile
        $animalFarmer = AnimalFarmer::create([
            'user_id' => auth()->id(),
            'phone' => $request->phone,
            'dob' => $request->dob,
            'gender' => $request->gender,
            'education' => $request->education,
            'household_size' => $request->household_size,
            'dependents' => $request->dependents,
            'income_level' => $request->income_level,
            'lga' => $request->lga,
            'herd_size' => $request->herd_size,
            'facility_type' => $request->facility_type,
            'breeding_program' => $request->breeding_program,
            'farm_location' => $request->farm_location,
            'livestock' => $request->livestock,  
            'other_livestock' => $request->other_livestock,  
        ]);

        
       
        // Update user status to "pending"
         auth()->user()->update(['status' => 'pending']);

        return redirect()->route('home')->with('success', 'Animal farmer profile updated successfully!');
    }


     // Show the form for abattoir operators
     public function showAbattoirOperatorForm()
    {
        // return view('farmers'); 
        return view('farmers.abattoir');
    }

    // Store abattoir operator data
    public function storeAbattoirOperator(Request $request)
    {
        $request->validate([
            'phone' => 'required|string|min:10|max:15',
            'dob' => 'required|date',
            'gender' => 'required|in:Male,Female,Other',
            'education' => 'required|string',
            'household_size' => 'required|integer',
            'dependents' => 'required|integer',
            'income_level' => 'required|string',
            'lga' => 'required|string',
            'facility_type' => 'required|string',
            'facility_specs' => 'required|string',
            'operational_capacity' => 'required|string', 
            'certifications' => 'nullable|array', 
        ]);

        // Create AbattoirOperator profile
        AbattoirOperator::create([
            'user_id' => auth()->id(),
            'phone' => $request->phone,
            'dob' => $request->dob,
            'gender' => $request->gender,
            'education' => $request->education,
            'household_size' => $request->household_size,
            'dependents' => $request->dependents,
            'income_level' => $request->income_level,
            'lga' => $request->lga,
            'facility_type' => $request->facility_type,
            'facility_specs' => $request->facility_specs,
            'operational_capacity' => $request->operational_capacity,
            'certifications' => $request->certifications,
        ]);

        // Update user status to "pending"
        auth()->user()->update(['status' => 'pending']);

        return redirect()->route('home')->with('success', 'Abattoir operator profile updated successfully!');
    }

    // Show the form for processors
    public function showProcessorForm()
    {
        // return view('farmers');
        return view('farmers.processor');
    }

    // Store processor data
    public function storeProcessor(Request $request)
    {
        $request->validate([
           'phone' => 'required|string|min:10|max:15',
            'dob' => 'required|date',
            'gender' => 'required|in:Male,Female,Other',
            'education' => 'required|string',
            'household_size' => 'required|integer',
            'dependents' => 'required|integer',
            'income_level' => 'required|string',
            'lga' => 'required|string',
            'processed_items' => 'required|json', 
            'processing_capacity' => 'required|numeric',
            'equipment_type' => 'required|string',
            'equipment_specs' => 'required|string', 
        ]);

        // Create Processor profile
        Processor::create([
            'user_id' => auth()->id(),
            'phone' => $request->phone,
            'dob' => $request->dob,
            'gender' => $request->gender,
            'education' => $request->education,
            'household_size' => $request->household_size,
            'dependents' => $request->dependents,
            'income_level' => $request->income_level,
            'lga' => $request->lga,
            'processed_items' => $request->processed_items,
            'processing_capacity' => $request->processing_capacity,
            'equipment_type' => $request->equipment_type,
            'equipment_specs' => $request->equipment_specs,
        ]);

        // Update user status to "pending"
        auth()->user()->update(['status' => 'pending']);

        return redirect()->route('home')->with('success', 'Processor profile updated successfully!');
    }


}






