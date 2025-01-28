<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Models\AgriculturalPractice;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    //

   public function index()
    {

        //fetch and display notifications
        // $notifications = auth()->user()->notifications;
        // return view('home', compact('notifications'));

        $user = Auth::user();

        // Fetch the user's registrations with their associated practices
        $registrations = $user->registrations()->with('practice')->get();

        // Fetch all agricultural practices for display
        $practices = AgriculturalPractice::all();

        return view('home', compact('user', 'registrations', 'practices'));
        
    }

}
