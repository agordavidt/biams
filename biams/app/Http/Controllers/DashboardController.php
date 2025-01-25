<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    //

   public function index()
    {

        //fetch and display notifications
        $notifications = auth()->user()->notifications;
        return view('home', compact('notifications'));
        // return view('home');
    }

}
