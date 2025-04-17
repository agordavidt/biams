<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Abattoir;
use App\Models\AbattoirStaff;
use App\Models\Livestock;
use App\Models\SlaughterOperation;
use App\Models\User;
use Illuminate\Http\Request;

class AbattoirController extends Controller
{
    public function index(Request $request)
    {
        $abattoirs = Abattoir::query()
            ->when($request->search, fn($q) => $q->where('name', 'like', "%{$request->search}%")
                ->orWhere('lga', 'like', "%{$request->search}%"))
            ->paginate(20);

        // Return JSON if AJAX request (for modal)
        if ($request->ajax()) {
            return response()->json($abattoirs);
        }

        return view('admin.abattoirs.index', compact('abattoirs'));
    }

    public function create()
    {
        // No longer needed as we're using modal
        abort(404);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'registration_number' => 'required|string|unique:abattoirs',
            'license_number' => 'required|string|unique:abattoirs',
            'address' => 'required|string|max:255',
            'lga' => 'required|string|max:100',
            'gps_latitude' => 'nullable|numeric',
            'gps_longitude' => 'nullable|numeric',
            'capacity' => 'required|integer|min:1',
            'status' => 'required|in:active,inactive,suspended',
            'description' => 'nullable|string',
        ]);

        $abattoir = Abattoir::create($validated);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Abattoir created successfully.',
                'data' => $abattoir
            ]);
        }

        return redirect()->route('admin.abattoirs.index')->with('success', 'Abattoir created successfully.');
    }

    public function edit(Abattoir $abattoir)
    {
        // Return JSON if AJAX request (for modal)
        if (request()->ajax()) {
            return response()->json($abattoir);
        }

        // Fallback to regular view if needed
        return view('admin.abattoirs.edit', compact('abattoir'));
    }

    public function update(Request $request, Abattoir $abattoir)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'registration_number' => 'required|string|unique:abattoirs,registration_number,' . $abattoir->id,
            'license_number' => 'required|string|unique:abattoirs,license_number,' . $abattoir->id,
            'address' => 'required|string|max:255',
            'lga' => 'required|string|max:100',
            'gps_latitude' => 'nullable|numeric',
            'gps_longitude' => 'nullable|numeric',
            'capacity' => 'required|integer|min:1',
            'status' => 'required|in:active,inactive,suspended',
            'description' => 'nullable|string',
        ]);

        $abattoir->update($validated);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Abattoir updated successfully.',
                'data' => $abattoir
            ]);
        }

        return redirect()->route('admin.abattoirs.index')->with('success', 'Abattoir updated successfully.');
    }

   
    public function manageStaff(Abattoir $abattoir)
    {
        $staff = $abattoir->staff()->get();
        return view('admin.abattoirs.staff', compact('abattoir', 'staff'));
    }

    public function assignStaff(Request $request, Abattoir $abattoir)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:abattoir_staff,email',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'role' => 'required|in:supervisor,meat_inspector,veterinary_officer,cleaner,security,other',
            'start_date' => 'required|date',
        ]);
    
        AbattoirStaff::create([
            'abattoir_id' => $abattoir->id,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'role' => $request->role,
            'start_date' => $request->start_date,
            'is_active' => true,
        ]);
    
        return redirect()->route('admin.abattoirs.staff', $abattoir)->with('success', 'Staff assigned successfully.');
    }


   public function removeStaff(Abattoir $abattoir, AbattoirStaff $staff)
    {
        $staff->delete();
        return redirect()->route('admin.abattoirs.staff', $abattoir)->with('success', 'Staff removed successfully.');
    }

    public function operations(Abattoir $abattoir)
    {
        $operations = SlaughterOperation::where('abattoir_id', $abattoir->id)
            ->with('livestock', 'slaughteredBy', 'supervisedBy')
            ->latest()
            ->paginate(20);

        $livestock = Livestock::where('status', 'approved')->get();
        $staff = $abattoir->staff()->where('is_active', true)->get();

        return view('admin.abattoirs.operations', compact('abattoir', 'operations', 'livestock', 'staff'));
    }


   public function storeOperation(Request $request, Abattoir $abattoir)
    {
        $request->validate([
            'livestock_id' => 'required|exists:livestock,id',
            'slaughter_date' => 'required|date',
            'slaughter_time' => 'required',
            'slaughtered_by' => 'required|exists:abattoir_staff,id',
            'supervised_by' => 'nullable|exists:abattoir_staff,id',
            'carcass_weight_kg' => 'nullable|numeric|min:0',
            'meat_grade' => 'required|in:premium,standard,economy,ungraded',
            'is_halal' => 'boolean',
            'is_kosher' => 'boolean',
            'notes' => 'nullable|string',
        ]);

        SlaughterOperation::create([
            'abattoir_id' => $abattoir->id,
            'livestock_id' => $request->livestock_id,
            'slaughter_date' => $request->slaughter_date,
            'slaughter_time' => $request->slaughter_time,
            'slaughtered_by' => $request->slaughtered_by,
            'supervised_by' => $request->supervised_by,
            'carcass_weight_kg' => $request->carcass_weight_kg,
            'meat_grade' => $request->meat_grade,
            'is_halal' => $request->boolean('is_halal'),
            'is_kosher' => $request->boolean('is_kosher'),
            'notes' => $request->notes,
        ]);

        Livestock::find($request->livestock_id)->update(['status' => 'slaughtered']);

        return redirect()->route('admin.abattoirs.operations', $abattoir)->with('success', 'Slaughter operation recorded successfully.');
    }
}