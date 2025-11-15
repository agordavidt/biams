<?php



namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class NewPasswordController extends Controller
{
    /**
     * Display the password reset view.
     */
    public function create(Request $request): View
    {
        return view('auth.reset-password', ['request' => $request]);
    }

    /**
     * Handle an incoming new password request.
     * 
     * ✅ OPTION A IMPLEMENTATION: Email reset does NOT bypass force password change
     * ✅ GRACEFUL ERROR HANDLING: System won't crash
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        try {
            // Attempt to reset the user's password
            $status = Password::reset(
                $request->only('email', 'password', 'password_confirmation', 'token'),
                function ($user) use ($request) {
                    // Update password and remember token
                    $user->forceFill([
                        'password' => Hash::make($request->password),
                        'remember_token' => Str::random(60),
                    ])->save();

                    // ✅ OPTION A: Do NOT set password_changed flag
                    // This ensures farmers (and future roles) still see force change page
                    // Email reset is for recovery, not onboarding completion

                    // Fire password reset event
                    event(new PasswordReset($user));

                    // Log successful password reset
                    Log::info('Password reset successful', [
                        'user_id' => $user->id,
                        'email' => $user->email,
                        'role' => $user->roles->first()->name ?? 'N/A',
                        'ip' => request()->ip(),
                        'timestamp' => now()
                    ]);
                }
            );

            // ✅ SUCCESS: Password reset successfully
            if ($status == Password::PASSWORD_RESET) {
                return redirect()
                    ->route('login')
                    ->with('status', 'Your password has been reset successfully! Please login with your new password.');
            }

            // ⚠️ Invalid token or other issue
            Log::warning('Password reset failed', [
                'email' => $request->email,
                'status' => $status,
                'ip' => $request->ip()
            ]);

            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => __($status)]);

        } catch (\Exception $e) {
            // ✅ CRITICAL: Catch any database or system errors
            Log::error('Password reset process failed', [
                'email' => $request->email,
                'error' => $e->getMessage(),
                'ip' => $request->ip(),
                'trace' => $e->getTraceAsString()
            ]);

            // ✅ Don't crash - return graceful error message
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'An error occurred while resetting your password. Please try again or contact support.']);
        }
    }
}