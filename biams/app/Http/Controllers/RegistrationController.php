<?php


namespace App\Http\Controllers;

use App\Models\AgriculturalPractice;
use App\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RegistrationController extends Controller
{
    // Show form for a specific practice
    public function showForm($practice_id)
    {
        $practice = AgriculturalPractice::findOrFail($practice_id);
        return view('registrations.form', compact('practice'));
    }

    // Store registration data
    public function store(Request $request, $practice_id)
    {
        $request->validate([
            'details' => 'required|json', // Validate JSON input
        ]);

        // Create a new registration
        Registration::create([
            'user_id' => Auth::id(),
            'practice_id' => $practice_id,
            'details' => $request->details,
            'status' => 'pending', // Default status
        ]);

        return redirect()->route('home')->with('success', 'Registration submitted successfully!');
    }

    // Show user's registration summary
    public function summary()
    {
        $registrations = Registration::where('user_id', Auth::id())->with('practice')->get();
        return view('registrations.summary', compact('registrations'));
    }
}