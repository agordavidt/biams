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
            // The 'status:onboarded' check has been removed from LoginRequest::authenticate()
            $request->authenticate(); 
            $request->session()->regenerate();
            
            $user = auth()->user();
            
            // Log successful login
            $this->logLoginAttempt($email, $user, $ipAddress, $userAgent, 'success');
            
            // Fetch user's role from Spatie for logging/context
            Log::info('User login attempt:', [
                'user_id' => $user->id,
                'email' => $user->email,
                // Get the name of the first role for logging/context
                'role' => $user->roles->first()->name ?? 'N/A' 
            ]);
            
            // Role-based redirects using Spatie's hasRole() method
            
            // Redirect for Super Admin
            if ($user->hasRole('Super Admin')) {
                return redirect()->route('super_admin.dashboard');
            }

            // Redirect for Governor
            if ($user->hasRole('Governor')) {
                return redirect()->route('governor.dashboard');
            }

            // Redirect for State Admin
            if ($user->hasRole('State Admin')) { 
                return redirect()->route('admin.dashboard');
            }
            
            // Redirect for LGA Admin
            if ($user->hasRole('LGA Admin')) { 
                // Redirecting to a dedicated LGA dashboard route
                return redirect()->route('lga_admin.dashboard'); 
            }

            // For regular users (check if they have the 'User' role)
            if ($user->hasRole('User')) { 
                // PROFILE COMPLETION LOGIC REMOVED: All standard users redirect to home immediately
                return redirect()->intended(RouteServiceProvider::HOME);
            }
            
            // Default redirect (e.g., if a user has a role not explicitly mapped here)
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