<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Jenssegers\Agent\Agent;

class LoginLog extends Model
{
    protected $fillable = [
        'email',
        'user_id',
        'ip_address',
        'user_agent',
        'device_type',
        'browser',
        'platform',
        'location',
        'status',
        'failure_reason',
        'is_suspicious',
        'metadata',
    ];

    protected $casts = [
        'is_suspicious' => 'boolean',
        'metadata' => 'array',
    ];

    /**
     * Get the user associated with this login log.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope for successful logins
     */
    public function scopeSuccessful($query)
    {
        return $query->where('status', 'success');
    }

    /**
     * Scope for failed logins
     */
    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    /**
     * Scope for blocked logins
     */
    public function scopeBlocked($query)
    {
        return $query->where('status', 'blocked');
    }

    /**
     * Scope for suspicious logins
     */
    public function scopeSuspicious($query)
    {
        return $query->where('is_suspicious', true);
    }

    /**
     * Scope for recent logins (last 24 hours)
     */
    public function scopeRecent($query)
    {
        return $query->where('created_at', '>=', now()->subDay());
    }

    /**
     * Get device information from user agent
     */
    public static function getDeviceInfo($userAgent = null)
    {
        if (!$userAgent) {
            $userAgent = request()->userAgent();
        }

        $agent = new Agent();
        $agent->setUserAgent($userAgent);

        return [
            'device_type' => $agent->isMobile() ? 'mobile' : ($agent->isTablet() ? 'tablet' : 'desktop'),
            'browser' => $agent->browser(),
            'platform' => $agent->platform(),
        ];
    }

    /**
     * Check if login attempt is suspicious
     */
    public static function isSuspicious($email, $ipAddress, $userAgent = null)
    {
        // Check for multiple failed attempts from same IP
        $recentFailedAttempts = self::where('email', $email)
            ->where('ip_address', $ipAddress)
            ->where('status', 'failed')
            ->where('created_at', '>=', now()->subMinutes(15))
            ->count();

        if ($recentFailedAttempts >= 5) {
            return true;
        }

        // Check for multiple failed attempts from different IPs
        $recentFailedIPs = self::where('email', $email)
            ->where('status', 'failed')
            ->where('created_at', '>=', now()->subMinutes(30))
            ->distinct('ip_address')
            ->count('ip_address');

        if ($recentFailedIPs >= 3) {
            return true;
        }

        // Check for unusual user agent patterns
        if ($userAgent) {
            $agent = new Agent();
            $agent->setUserAgent($userAgent);
            
            // Check for common bot user agents
            $botPatterns = ['bot', 'crawler', 'spider', 'scraper'];
            foreach ($botPatterns as $pattern) {
                if (stripos($userAgent, $pattern) !== false) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Get login statistics
     */
    public static function getStatistics($days = 30)
    {
        $startDate = now()->subDays($days);

        return [
            'total_attempts' => self::where('created_at', '>=', $startDate)->count(),
            'successful_logins' => self::where('status', 'success')->where('created_at', '>=', $startDate)->count(),
            'failed_attempts' => self::where('status', 'failed')->where('created_at', '>=', $startDate)->count(),
            'blocked_attempts' => self::where('status', 'blocked')->where('created_at', '>=', $startDate)->count(),
            'suspicious_attempts' => self::where('is_suspicious', true)->where('created_at', '>=', $startDate)->count(),
            'unique_users' => self::where('status', 'success')->where('created_at', '>=', $startDate)->distinct('user_id')->count('user_id'),
            'unique_ips' => self::where('created_at', '>=', $startDate)->distinct('ip_address')->count('ip_address'),
        ];
    }

    /**
     * Get top failed login attempts by email
     */
    public static function getTopFailedEmails($limit = 10)
    {
        return self::where('status', 'failed')
            ->selectRaw('email, COUNT(*) as attempt_count')
            ->groupBy('email')
            ->orderByDesc('attempt_count')
            ->limit($limit)
            ->get();
    }

    /**
     * Get top suspicious IP addresses
     */
    public static function getTopSuspiciousIPs($limit = 10)
    {
        return self::where('is_suspicious', true)
            ->selectRaw('ip_address, COUNT(*) as attempt_count')
            ->groupBy('ip_address')
            ->orderByDesc('attempt_count')
            ->limit($limit)
            ->get();
    }
} 