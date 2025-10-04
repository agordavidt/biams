<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Farmer;
use App\Models\FarmLand;
use App\Models\LGA;
use App\Models\Cooperative;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; 

class FarmerController extends Controller
{
    public function index(Request $request)
    {
        $query = Farmer::with(['lga', 'cooperative', 'farmLands'])
            ->latest();

        // Search filter
        if ($request->has('search') && $request->search) {
            $query->where(function($q) use ($request) {
                $q->where('full_name', 'like', '%' . $request->search . '%')
                  ->orWhere('nin', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%')
                  ->orWhere('phone_primary', 'like', '%' . $request->search . '%');
            });
        }

        // LGA filter
        if ($request->has('lga_id') && $request->lga_id) {
            $query->where('lga_id', $request->lga_id);
        }

        // Farm type filter
        if ($request->has('farm_type') && $request->farm_type) {
            $query->whereHas('farmLands', function($q) use ($request) {
                $q->where('farm_type', $request->farm_type);
            });
        }

        // Crop type filter (for crop farmers)
        if ($request->has('crop_type') && $request->crop_type) {
            $query->whereHas('farmLands.cropPracticeDetails', function($q) use ($request) {
                $q->where('crop_type', 'like', '%' . $request->crop_type . '%');
            });
        }

        // Animal type filter (for livestock farmers)
        if ($request->has('animal_type') && $request->animal_type) {
            $query->whereHas('farmLands.livestockPracticeDetails', function($q) use ($request) {
                $q->where('animal_type', 'like', '%' . $request->animal_type . '%');
            });
        }

        // Status filter
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        $farmers = $query->paginate(20);

        // Statistics
        $stats = [
            'totalFarmers' => Farmer::count(),
            'activeFarmers' => Farmer::where('status', 'active')->count(),
            'pendingApproval' => Farmer::where('status', 'pending_lga_review')->count(),
            'byFarmType' => FarmLand::select('farm_type', DB::raw('count(distinct farmer_id) as farmer_count'))
                ->groupBy('farm_type')
                ->get(),
            'byLGA' => Farmer::select('lga_id', DB::raw('count(*) as count'))
                ->with('lga')
                ->groupBy('lga_id')
                ->get(),
        ];

        $lgas = LGA::all();
        $farmTypes = ['crops', 'livestock', 'fisheries', 'orchards'];
        
        // Get unique crop types for filter
        $cropTypes = DB::table('crop_practice_details')
            ->select('crop_type')
            ->distinct()
            ->pluck('crop_type');
            
        // Get unique animal types for filter
        $animalTypes = DB::table('livestock_practice_details')
            ->select('animal_type')
            ->distinct()
            ->pluck('animal_type');

        return view('admin.farmers.index', compact(
            'farmers',
            'stats',
            'lgas',
            'farmTypes',
            'cropTypes',
            'animalTypes'
        ));
    }

    public function show(Farmer $farmer)
    {
        $farmer->load([
            'lga',
            'cooperative',
            'farmLands' => function($query) {
                $query->with(['cropPracticeDetails', 'livestockPracticeDetails', 'fisheriesPracticeDetails', 'orchardPracticeDetails']);
            },
            'enrolledBy',
            'approvedBy'
        ]);

        return view('admin.farmers.show', compact('farmer'));
    }
}