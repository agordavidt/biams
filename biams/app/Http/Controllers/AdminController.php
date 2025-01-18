<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Farmers\CropFarmer;
use App\Models\Farmers\AnimalFarmer;
use App\Models\Farmers\AbattoirOperator;
use App\Models\Farmers\Processor;
use Illuminate\Http\Request;


class AdminController extends Controller
{
    //
    public function index()
    {
        return view('admin.index');
    }

     // Crop Farmers Applications
    public function cropFarmers()
    {
        $applications = CropFarmer::with('user')->get();
        return view('admin.applications.crop-farmers', compact('applications'));
    }

    // Animal Farmers Applications
    public function animalFarmers()
    {
        $applications = AnimalFarmer::with('user')->get();
        return view('admin.applications.animal-farmers', compact('applications'));
    }

    // Abattoir Operators Applications
    public function abattoirOperators()
    {
        $applications = AbattoirOperator::with('user')->get();
        return view('admin.applications.abattoir-operators', compact('applications'));
    }

    // Processors Applications
    public function processors()
    {
        $applications = Processor::with('user')->get();
        return view('admin.applications.processors', compact('applications'));
    }

    // Approve an application
    public function approve(User $user)
    {
        $user->update(['status' => 'approved']);
        // Send approval notification (email or dashboard)
        return redirect()->back()->with('success', 'Application approved successfully.');
    }

    // Reject an application
    public function reject(User $user)
    {
        $user->update(['status' => 'rejected']);
        // Send rejection notification (email or dashboard)
        return redirect()->back()->with('success', 'Application rejected successfully.');
    }


}

