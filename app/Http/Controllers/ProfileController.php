<?php


namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Show the profile completion form.
     */
   /**
 * Show the profile completion form.
 */
public function showCompleteForm(): View
{
    return view('profile.complete', [
        'user' => auth()->user(),
    ]);
}

    /**
     * Handle profile completion.
     */
    public function complete(Request $request): RedirectResponse
    {
        $request->validate([
            'phone' => 'required|string|min:10|max:15',
            'dob' => 'required|date',
             'nin' => ['required', 'string'],
            'gender' => 'required|string|in:Male,Female,Other',
            'education' => 'required|string',
            'household_size' => 'required|integer|min:1',
            'dependents' => 'required|integer|min:0',
            'income_level' => 'required|string',
            'lga' => 'required|string',
        ]);

        // Create the user's profile
        auth()->user()->profile()->create($request->all());

        return redirect()->route('home')->with('success', 'Profile completed successfully!');
    }

    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        // Fetch the user's profile data
        $user = $request->user();
        $profile = $user->profile;

        return view('profile.edit', [
            'user' => $user,
            'profile' => $profile,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        // Validate the request data
        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($request->user()->id)],
            'phone' => ['required', 'string', 'min:10', 'max:15'],
            'dob' => ['required', 'date'],
            'gender' => ['required', 'string', 'in:Male,Female,Other'],
            'education' => ['required', 'string'],
            'household_size' => ['required', 'integer', 'min:1'],
            'dependents' => ['required', 'integer', 'min:0'],
            'income_level' => ['required', 'string'],
            'lga' => ['required', 'string'],
        ]);

        // Update the user's core information (name and email)
        $user = $request->user();
        $user->name = $validatedData['name'];
        $user->email = $validatedData['email'];

        // Reset email verification if the email was changed
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        // Save the updated user data
        $user->save();

        // Update or create the user's profile
        $profileData = [
            'phone' => $validatedData['phone'],
            'dob' => $validatedData['dob'],
            'gender' => $validatedData['gender'],
            'education' => $validatedData['education'],
            'household_size' => $validatedData['household_size'],
            'dependents' => $validatedData['dependents'],
            'income_level' => $validatedData['income_level'],
            'lga' => $validatedData['lga'],
        ];

        // Update or create the profile
        if ($user->profile) {
            $user->profile->update($profileData);
        } else {
            $user->profile()->create($profileData);
        }

        return Redirect::route('home')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        // Delete the user's profile (if it exists)
        if ($user->profile) {
            $user->profile->delete();
        }

        // Delete the user
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}