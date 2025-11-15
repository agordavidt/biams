<?php





namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle an incoming password reset link request.
     * 
     * ✅ GRACEFUL ERROR HANDLING: System won't crash even if email fails
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        try {
            // Attempt to send the password reset link
            $status = Password::sendResetLink(
                $request->only('email')
            );

            // ✅ SUCCESS: Email sent successfully
            if ($status == Password::RESET_LINK_SENT) {
                Log::info('Password reset link sent successfully', [
                    'email' => $request->email,
                    'ip' => $request->ip(),
                    'timestamp' => now()
                ]);

                return back()->with('status', 'We have sent you a password reset link! Please check your email.');
            }

            // ⚠️ User not found or other issue
            Log::warning('Password reset link request failed', [
                'email' => $request->email,
                'status' => $status,
                'ip' => $request->ip()
            ]);

            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => __($status)]);

        } catch (\Exception $e) {
            // ✅ CRITICAL: Catch any email sending errors (SMTP failures, etc.)
            Log::error('Password reset email failed to send', [
                'email' => $request->email,
                'error' => $e->getMessage(),
                'ip' => $request->ip(),
                'trace' => $e->getTraceAsString()
            ]);

            // ✅ Don't crash - return graceful message to user
            return back()
                ->withInput($request->only('email'))
                ->with('status', 'If an account exists with this email, you will receive a password reset link shortly.');
        }
    }
}

