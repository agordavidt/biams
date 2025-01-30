<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\AgriculturalPractice;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Fetch the user's registrations for each agricultural practice
        $cropFarmers = $user->cropFarmers;
        $animalFarmers = $user->animalFarmers;
        $abattoirOperators = $user->abattoirOperators;
        $processors = $user->processors;

        // Combine all registrations into a single collection for easy display
        $registrations = collect()
            ->merge($cropFarmers->map(function ($registration) {
                $registration->type = 'Crop Farmer';
                return $registration;
            }))
            ->merge($animalFarmers->map(function ($registration) {
                $registration->type = 'Animal Farmer';
                return $registration;
            }))
            ->merge($abattoirOperators->map(function ($registration) {
                $registration->type = 'Abattoir Operator';
                return $registration;
            }))
            ->merge($processors->map(function ($registration) {
                $registration->type = 'Processor';
                return $registration;
            }));


        return view('home', compact('user', 'registrations'));
    }

}

 