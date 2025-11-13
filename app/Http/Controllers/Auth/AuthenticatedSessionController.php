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
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        $email = $request->email;
        $ipAddress = $request->ip();
        $userAgent = $request->userAgent();
        
        try {
            $request->authenticate(); 
            $request->session()->regenerate();
            
            $user = auth()->user();
            
            $this->logLoginAttempt($email, $user, $ipAddress, $userAgent, 'success');
            
            Log::info('User login attempt:', [
                'user_id' => $user->id,
                'email' => $user->email,
                'role' => $user->roles->first()->name ?? 'N/A' 
            ]);
            
            // Role-based redirects
            
            if ($user->hasRole('Super Admin')) {
                return redirect()->route('super_admin.dashboard');
            }

            if ($user->hasRole('Governor')) {
                return redirect()->route('governor.dashboard');
            }

            if ($user->hasRole('Commissioner')) {
                return redirect()->route('commissioner.dashboard');
            }

            if ($user->hasRole('State Admin')) { 
                return redirect()->route('admin.dashboard');
            }
            
            if ($user->hasRole('LGA Admin')) { 
                return redirect()->route('lga_admin.dashboard'); 
            }

            if ($user->hasRole('Enrollment Agent')) {
                return redirect()->route('enrollment.dashboard');
            }

          
            if ($user->hasRole('Vendor Manager')) {
                return redirect()->route('vendor.dashboard');
            }

           
            if ($user->hasRole('Distribution Agent')) {
                return redirect()->route('vendor.distribution.dashboard');
            }
            
       
            if ($user->hasRole('User')) { 
                $farmer = $user->farmerProfile;

                if ($farmer && $farmer->password_changed === false) {
                    return redirect()->route('password.force_change');
                }
                
                return redirect()->route('farmer.dashboard'); 
            }
            
            return redirect()->intended(RouteServiceProvider::HOME);            
            
        } catch (ValidationException $e) {
            $this->logLoginAttempt($email, null, $ipAddress, $userAgent, 'failed', 'Invalid credentials');
            throw $e;
        }
    }

    public function destroy(Request $request): RedirectResponse
    {
        $user = auth()->user();
        
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($user) {
            $this->logLoginAttempt($user->email, $user, $request->ip(), $request->userAgent(), 'logout');
        }

        return redirect('/');
    }
    
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
            \Log::error('Failed to log login attempt: ' . $e->getMessage());
        }
    }
}