<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\AgriculturalPractice;
use Illuminate\Http\Request;


class DashboardController extends Controller
{

    protected $user; // Declare the $user property to use on showApplicationDetails
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
                return $registration;
            }))
            ->merge($animalFarmers->map(function ($registration) {
                $registration->type = 'Animal Farming';
                return $registration;
            }))
            ->merge($abattoirOperators->map(function ($registration) {
                $registration->type = 'Abattoir Operator';
                return $registration;
            }))
            ->merge($processors->map(function ($registration) {
                $registration->type = 'Processing & Value Addition';
                return $registration;
            }));


        return view('home', compact('user', 'registrations'));
    }




    public function showApplicationDetails($id)
        {
            // Fetch the registration based on the ID
            $registration = collect()
                ->merge($this->user->cropFarmers)
                ->merge($this->user->animalFarmers)
                ->merge($this->user->abattoirOperators)
                ->merge($this->user->processors)
                ->firstWhere('id', $id);

            if (!$registration) {
                abort(404, 'Application not found.');
            }

            return view('application.details', compact('registration'));
        }
}

 