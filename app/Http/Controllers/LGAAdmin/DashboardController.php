<?php

namespace App\Http\Controllers\LGAAdmin;

use App\Http\Controllers\Controller;
use App\Models\Farmer; 
use App\Models\LGA;  

class DashboardController extends Controller
{
    public function index()
    {
        $adminUser = auth()->user();
        $lgaId = $adminUser->administrative_id;

        // Fetch LGA Name for the welcome card
        $lgaName = LGA::find($lgaId)->name ?? 'Unknown';

        // Base query scoped to the admin's LGA
        $baseQuery = Farmer::forLGA($lgaId);

        // Calculate the key metrics
        $pendingCount = (clone $baseQuery)->where('status', 'pending_lga_review')->count();
        $rejectedCount = (clone $baseQuery)->where('status', 'rejected')->count();
        $activeCount = (clone $baseQuery)->where('status', 'active')->count();

        return view('lga_admin.dashboard', compact('lgaName', 'pendingCount', 'rejectedCount', 'activeCount'));
    }
}