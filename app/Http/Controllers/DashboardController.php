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
                $registration->type = 'Crop Farming';
                $registration->type_slug = 'crop'; // Add slug for routing
                return $registration;
            }))
            ->merge($animalFarmers->map(function ($registration) {
                $registration->type = 'Animal Farming';
                $registration->type_slug = 'animal'; // Add slug for routing
                return $registration;
            }))
            ->merge($abattoirOperators->map(function ($registration) {
                $registration->type = 'Abattoir Operator';
                $registration->type_slug = 'abattoir'; // Add slug for routing
                return $registration;
            }))
            ->merge($processors->map(function ($registration) {
                $registration->type = 'Processing & Value Addition';
                $registration->type_slug = 'processor'; // Add slug for routing
                return $registration;
            }));

        return view('home', compact('user', 'registrations'));
    }
}