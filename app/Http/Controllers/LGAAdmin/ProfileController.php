<?php

namespace App\Http\Controllers\LGAAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    /**
     * Display the LGA Admin's profile/settings page
     */
    public function index()
    {
        $user = Auth::user();
        
        return view('lga_admin.profile', [
            'user' => $user,
            'lga' => $user->administrative,
        ]);
    }

    /**
     * Update the LGA Admin's profile information
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'phone_number' => 'nullable|string|max:20|unique:users,phone_number,' . $user->id,
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user->update([
            'name' => $request->name,
            'phone_number' => $request->phone_number,
        ]);

        return redirect()->back()
            ->with('success', 'Profile updated successfully.');
    }
}