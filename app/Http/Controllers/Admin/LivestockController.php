<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Abattoir;
use App\Models\AbattoirStaff;
use App\Models\AnteMortemInspection;
use App\Models\Livestock;
use App\Models\PostMortemInspection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;






class LivestockController extends Controller
{
    public function index(Request $request)
    {
        $livestock = Livestock::query()
            ->when($request->search, fn($q) => $q->where('tracking_id', 'like', "%{$request->search}%")
                ->orWhere('species', 'like', "%{$request->search}%")
                ->orWhere('origin_lga', 'like', "%{$request->search}%"))
            ->with('registeredBy')
            ->latest()
            ->paginate(20);

        return view('admin.livestock.index', compact('livestock'));
    }

    public function create()
    {
        return view('admin.livestock.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'species' => 'required|in:cattle,goat,sheep,pig,other',
            'breed' => 'nullable|string|max:100',
            'origin_location' => 'required|string|max:255',
            'origin_lga' => 'required|string|max:100',
            'origin_state' => 'required|string|max:100',
            'owner_name' => 'required|string|max:255',
            'owner_phone' => 'nullable|string|max:20',
            'owner_address' => 'nullable|string|max:255',
            'registration_date' => 'required|date',
            'estimated_weight_kg' => 'nullable|numeric|min:0',
            'estimated_age_months' => 'nullable|integer|min:0',
            'gender' => 'required|in:male,female',
        ]);

        Livestock::create([
            'tracking_id' => Livestock::generateTrackingId(),
            'species' => $request->species,
            'breed' => $request->breed,
            'origin_location' => $request->origin_location,
            'origin_lga' => $request->origin_lga,
            'origin_state' => $request->origin_state,
            'owner_name' => $request->owner_name,
            'owner_phone' => $request->owner_phone,
            'owner_address' => $request->owner_address,
            'registered_by' => Auth::id(),
            'registration_date' => $request->registration_date,
            'estimated_weight_kg' => $request->estimated_weight_kg,
            'estimated_age_months' => $request->estimated_age_months,
            'gender' => $request->gender,
            'status' => 'registered',
        ]);

        return redirect()->route('admin.livestock.index')->with('success', 'Livestock registered successfully.');
    }

    public function edit(Livestock $livestock)
    {
        return view('admin.livestock.edit', compact('livestock'));
    }

    public function update(Request $request, Livestock $livestock)
    {
        $request->validate([
            'species' => 'required|in:cattle,goat,sheep,pig,other',
            'breed' => 'nullable|string|max:255',
            'origin_location' => 'required|string|max:255',
            'origin_lga' => 'required|string|max:255',
            'origin_state' => 'required|string|max:255',
            'owner_name' => 'required|string|max:255',
            'owner_phone' => 'nullable|string|max:20',
            'owner_address' => 'nullable|string|max:500',
            'registration_date' => 'required|date',
            'estimated_weight_kg' => 'nullable|numeric|min:0',
            'estimated_age_months' => 'nullable|integer|min:0',
            'gender' => 'required|in:male,female',
        ]);

        $livestock->update($request->only([
            'species', 'breed', 'origin_location', 'origin_lga', 'origin_state',
            'owner_name', 'owner_phone', 'owner_address', 'registration_date',
            'estimated_weight_kg', 'estimated_age_months', 'gender',
        ]));

        return redirect()->route('admin.livestock.index')->with('success', 'Livestock updated successfully.');
    }


    public function inspections(Livestock $livestock)
    {
        $anteInspections = $livestock->anteMortemInspections()->with('abattoir', 'inspector')->get();
        $postInspections = $livestock->postMortemInspections()->with('abattoir', 'inspector')->get();
        $abattoirs = Abattoir::where('status', 'active')->get();
        
        // Get the slaughter operation's abattoir if exists
        $slaughterAbattoir = $livestock->slaughterOperation ? $livestock->slaughterOperation->abattoir : null;
        
        $inspectors = AbattoirStaff::whereIn('role', ['veterinary_officer', 'meat_inspector'])
                        ->where('is_active', true)
                        ->get();

        return view('admin.livestock.inspections', compact('livestock', 'anteInspections', 'postInspections', 'abattoirs', 'inspectors', 'slaughterAbattoir'  ));
    }

    // public function inspections(Livestock $livestock)
    // {
    //     $anteInspections = $livestock->anteMortemInspections()->with('abattoir', 'inspector')->get();
    //     $postInspections = $livestock->postMortemInspections()->with('abattoir', 'inspector')->get();
    //     $abattoirs = Abattoir::where('status', 'active')->get();
    //     $inspectors = AbattoirStaff::whereIn('role', ['veterinary_officer', 'meat_inspector'])->where('is_active', true)->get();

    //     return view('admin.livestock.inspections', compact('livestock', 'anteInspections', 'postInspections', 'abattoirs', 'inspectors'));
    // }

    public function storeAnteMortemInspection(Request $request, Livestock $livestock)
    {
        $request->validate([
            'abattoir_id' => 'required|exists:abattoirs,id',
            'inspector_id' => 'required|exists:abattoir_staff,id',
            'inspection_date' => 'required|date',
            'temperature' => 'nullable|numeric|min:0',
            'heart_rate' => 'nullable|integer|min:0',
            'respiratory_rate' => 'nullable|integer|min:0',
            'general_appearance' => 'required|in:normal,abnormal',
            'is_alert' => 'boolean',
            'has_lameness' => 'boolean',
            'has_visible_injuries' => 'boolean',
            'has_abnormal_discharge' => 'boolean',
            'decision' => 'required|in:approved,rejected,conditional',
            'rejection_reason' => 'nullable|string|max:1000',
            'notes' => 'nullable|string|max:1000',
        ]);

        $inspection = AnteMortemInspection::create([
            'livestock_id' => $livestock->id,
            'abattoir_id' => $request->abattoir_id,
            'inspector_id' => $request->inspector_id,
            'inspection_date' => $request->inspection_date,
            'temperature' => $request->temperature,
            'heart_rate' => $request->heart_rate,
            'respiratory_rate' => $request->respiratory_rate,
            'general_appearance' => $request->general_appearance,
            'is_alert' => $request->boolean('is_alert'),
            'has_lameness' => $request->boolean('has_lameness'),
            'has_visible_injuries' => $request->boolean('has_visible_injuries'),
            'has_abnormal_discharge' => $request->boolean('has_abnormal_discharge'),
            'decision' => $request->decision,
            'rejection_reason' => $request->rejection_reason,
            'notes' => $request->notes,
        ]);

        $livestock->update(['status' => $inspection->decision === 'approved' ? 'approved' : 'rejected']);

        return redirect()->route('admin.livestock.inspections', $livestock)->with('success', 'Ante-mortem inspection recorded.');
    }

    public function storePostMortemInspection(Request $request, Livestock $livestock)
    {
        $request->validate([
            'abattoir_id' => 'required|exists:abattoirs,id',
            'inspector_id' => 'required|exists:abattoir_staff,id',
            'inspection_date' => 'required|date',
            'carcass_normal' => 'boolean',
            'organs_normal' => 'boolean',
            'lymph_nodes_normal' => 'boolean',
            'has_parasites' => 'boolean',
            'has_disease_signs' => 'boolean',
            'abnormality_details' => 'nullable|string|max:1000',
            'decision' => 'required|in:fit_for_consumption,unfit_for_consumption,partially_fit',
            'rejection_reason' => 'nullable|string|max:1000',
            'notes' => 'nullable|string|max:1000',
            'stamp_number' => 'nullable|string|max:50',
        ]);

        PostMortemInspection::create([
            'livestock_id' => $livestock->id,
            'abattoir_id' => $request->abattoir_id,
            'inspector_id' => $request->inspector_id,
            'inspection_date' => $request->inspection_date,
            'carcass_normal' => $request->boolean('carcass_normal'),
            'organs_normal' => $request->boolean('organs_normal'),
            'lymph_nodes_normal' => $request->boolean('lymph_nodes_normal'),
            'has_parasites' => $request->boolean('has_parasites'),
            'has_disease_signs' => $request->boolean('has_disease_signs'),
            'abnormality_details' => $request->abnormality_details,
            'decision' => $request->decision,
            'rejection_reason' => $request->rejection_reason,
            'notes' => $request->notes,
            'stamp_number' => $request->stamp_number,
        ]);

        return redirect()->route('admin.livestock.inspections', $livestock)->with('success', 'Post-mortem inspection recorded.');
    }
}