<?php

namespace App\Http\Controllers\LGAAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the LGA Admin dashboard.
     */
    public function index(): View
    {
        // Typically, this would fetch data scoped to the user's LGA
        // using Auth::user()->administrative_id and administrative_type (LGA::class)
        $user = auth()->user();
        $lgaName = $user->administrative?->name ?? 'Unknown LGA';

        return view('lga_admin.dashboard', compact('lgaName'));
    }
}
