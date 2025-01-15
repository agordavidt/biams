<?php


namespace App\Http\Controllers;

use App\Models\Farmers\Crop;
use App\Models\Farmers\Livestock;
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
        $crops = Crop::all();
        return view('farmers', compact('crops'));
    }

    // Store crop farmer data
    public function storeCropFarmer(Request $request)
    {
        $request->validate([
            'crops' => 'required|array',
            'crops.*' => 'exists:crops,id',
            // Other validation rules
        ]);

        // Create CropFarmer profile
        $cropFarmer = CropFarmer::create([
            'user_id' => auth()->id(),
            // Other fields
        ]);

        // Attach selected crops
        $cropFarmer->crops()->sync($request->crops);

        return redirect()->route('home')->with('success', 'Crop farmer profile updated successfully!');
    }

    // Show the form for animal farmers
    public function showAnimalFarmerForm()
    {
        $livestock = Livestock::all();
        return view('farmers', compact('livestock'));
    }

    // Store animal farmer data
    public function storeAnimalFarmer(Request $request)
    {
        $request->validate([
            'livestock' => 'required|array',
            'livestock.*' => 'exists:livestock,id',
            // Other validation rules
        ]);

        // Create AnimalFarmer profile
        $animalFarmer = AnimalFarmer::create([
            'user_id' => auth()->id(),
            // Other fields
        ]);

        // Attach selected livestock
        $animalFarmer->livestock()->sync($request->livestock);

        return redirect()->route('home')->with('success', 'Animal farmer profile updated successfully!');
    }
}
