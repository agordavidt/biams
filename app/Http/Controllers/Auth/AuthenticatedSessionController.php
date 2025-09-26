<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use App\Models\LoginLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $email = $request->email;
        $ipAddress = $request->ip();
        $userAgent = $request->userAgent();
        
        try {
            $request->authenticate();
            $request->session()->regenerate();
            
            $user = auth()->user();
            
            // Log successful login
            $this->logLoginAttempt($email, $user, $ipAddress, $userAgent, 'success');
            
            Log::info('User login attempt:', [
                'user_id' => $user->id,
                'email' => $user->email,
                'role' => $user->role
            ]);
            
            // Role-based redirects for admin users (no profile completion needed)
            if ($user->role === 'super_admin') {
                return redirect()->route('super_admin.dashboard');
            }

            if ($user->role === 'governor') {
                return redirect()->route('governor.dashboard');
            }

            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }

            // For regular users - check profile completion first
            if ($user->role === 'user') {
                if (!$user->profile) {
                    return redirect()->route('profile.complete')->with('info', 'Please complete your profile to continue.');
                }
                
                // User has completed profile, redirect to home
                return redirect()->intended(RouteServiceProvider::HOME);
            }
            
            // Default redirect
            return redirect()->intended(RouteServiceProvider::HOME);
            
        } catch (ValidationException $e) {
            // Log failed login attempt
            $this->logLoginAttempt($email, null, $ipAddress, $userAgent, 'failed', 'Invalid credentials');
            
            throw $e;
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $user = auth()->user();
        
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Log logout if user was authenticated
        if ($user) {
            $this->logLoginAttempt($user->email, $user, $request->ip(), $request->userAgent(), 'logout');
        }

        return redirect('/');
    }

    /**
     * Log login attempt
     */
    private function logLoginAttempt($email, $user = null, $ipAddress = null, $userAgent = null, $status = 'failed', $failureReason = null)
    {
        try {
            $deviceInfo = LoginLog::getDeviceInfo($userAgent);
            $isSuspicious = LoginLog::isSuspicious($email, $ipAddress, $userAgent);
            
            LoginLog::create([
                'email' => $email,
                'user_id' => $user ? $user->id : null,
                'ip_address' => $ipAddress,
                'user_agent' => $userAgent,
                'device_type' => $deviceInfo['device_type'],
                'browser' => $deviceInfo['browser'],
                'platform' => $deviceInfo['platform'],
                'status' => $status,
                'failure_reason' => $failureReason,
                'is_suspicious' => $isSuspicious,
                'metadata' => [
                    'session_id' => session()->getId(),
                    'timestamp' => now()->toISOString(),
                ],
            ]);
        } catch (\Exception $e) {
            // Log the error but don't break the authentication flow
            \Log::error('Failed to log login attempt: ' . $e->getMessage());
        }
    }
}