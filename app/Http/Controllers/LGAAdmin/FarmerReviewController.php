<?php

namespace App\Http\Controllers\LGAAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Farmer;
use App\Models\User;
use App\Models\LGA;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class FarmerReviewController extends Controller
{
    protected $cacheTTL = 300;
    
    /**
     * Display a listing of farmers with optimized queries
     */
    public function index(Request $request)
    {
        $lgaId = auth()->user()->administrative_id;
        
        $data = Cache::remember("lga_admin_farmers_{$lgaId}", $this->cacheTTL, function () use ($lgaId) {
            return $this->getFarmerData($lgaId);
        });

        return view('lga_admin.farmers.index', $data);
    }

    /**
     * Optimized data fetching
     */
    private function getFarmerData($lgaId)
    {
        $baseQuery = Farmer::forLGA($lgaId)
            ->select(['id', 'full_name', 'nin', 'email', 'status', 'created_at', 'enrolled_by', 'approved_at', 'initial_password'])
            ->with(['enrolledBy:id,name', 'approvedBy:id,name']);

        $counts = [
            'pending' => (clone $baseQuery)->pendingReview()->count(),
            'rejected' => (clone $baseQuery)->where('status', 'rejected')->count(),
            'active' => (clone $baseQuery)->where('status', 'active')->count(),
            'pending_activation' => (clone $baseQuery)->where('status', 'pending_activation')->count(),
        ];

        $pendingFarmers = (clone $baseQuery)
            ->pendingReview()
            ->orderBy('created_at', 'asc')
            ->paginate(20, ['*'], 'pending_page');

        $reviewedFarmers = (clone $baseQuery)
            ->whereIn('status', ['rejected', 'pending_activation', 'active', 'suspended'])
            ->orderBy('approved_at', 'desc')
            ->paginate(20, ['*'], 'reviewed_page');

        return [
            'pendingFarmers' => $pendingFarmers,
            'reviewedFarmers' => $reviewedFarmers,
            'counts' => $counts
        ];
    }

    /**
     * Display farmer profile
     */
    public function show(Farmer $farmer)
    {
        if ($farmer->lga_id !== auth()->user()->administrative_id) {
            abort(403, 'Unauthorized access to farmer outside your LGA.');
        }

        $farmer->load([
            'enrolledBy:id,name,email',
            'approvedBy:id,name',
            'cooperative:id,name',
            'farmLands' => function ($query) {
                $query->select(['id', 'farmer_id', 'name', 'farm_type', 'total_size_hectares', 'ownership_status', 'geolocation_geojson'])
                    ->with([
                        'cropPracticeDetails',
                        'livestockPracticeDetails', 
                        'fisheriesPracticeDetails',
                        'orchardPracticeDetails'
                    ]);
            }
        ]);

        return view('lga_admin.farmers.show', compact('farmer'));
    }

    /**
     * Approve farmer enrollment AND create user account immediately
     */
    public function approve(Farmer $farmer)
    {
        if (!$this->canProcessFarmer($farmer, 'pending_lga_review')) {
            return back()->with('error', 'Enrollment is not eligible for approval.');
        }

        // Check if user already exists
        if (User::where('email', $farmer->email)->exists()) {
            return back()->with('error', 'A user account with this email already exists.');
        }

        try {
            DB::beginTransaction();

            $admin = auth()->user();
            
            // 1. Create user account first
            $user = $this->createFarmerUserAccount($farmer);
            
            // 2. Approve farmer and link to user account
            $farmer->status = 'pending_activation';
            $farmer->approved_by = $admin->id;
            $farmer->approved_at = now();
            $farmer->user_id = $user->id; // Link the user account
            $farmer->save();

            Log::info('Farmer approved and user account created', [
                'farmer_id' => $farmer->id,
                'user_id' => $user->id,
                'admin_id' => $admin->id,
            ]);

            DB::commit();

            $this->clearFarmerCache($farmer->lga_id);

            return back()->with('success', 
                "Farmer profile approved and user account created!\n" .
                "Login credentials for the farmer:\n" .
                "Email: {$farmer->email}\n" .
                "Password: {$farmer->initial_password}\n\n" .
                "The farmer can now login and will be forced to change their password."
            );

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Farmer approval failed: ' . $e->getMessage());
            return back()->with('error', 'Approval failed: ' . $e->getMessage());
        }
    }

    /**
     * Reject farmer enrollment
     */
    public function reject(Request $request, Farmer $farmer)
    {
        $request->validate([
            'rejection_reason' => ['required', 'string', 'min:10', 'max:500']
        ]);

        if (!$this->canProcessFarmer($farmer, 'pending_lga_review')) {
            return back()->with('error', 'Enrollment is not eligible for rejection.');
        }

        DB::transaction(function () use ($farmer, $request) {
            $admin = auth()->user();
            $farmer->reject($admin, $request->rejection_reason);
            
            Log::info('Farmer enrollment rejected', [
                'farmer_id' => $farmer->id,
                'admin_id' => $admin->id,
                'reason' => $request->rejection_reason,
            ]);
        });

        $this->clearFarmerCache($farmer->lga_id);

        return back()->with('success', 'Farmer profile rejected. Enrollment Officer can now resubmit.');
    }

   

    /**
     * Create farmer user account with proper user status
     */
    private function createFarmerUserAccount(Farmer $farmer): User
    {
        return User::create([
            'name' => $farmer->full_name,
            'email' => $farmer->email,
            'phone_number' => $farmer->phone_primary,
            'password' => Hash::make($farmer->initial_password),
            'email_verified_at' => now(),
            'status' => 'onboarded', // User status, not farmer status
            'administrative_id' => $farmer->lga_id,
            'administrative_type' => LGA::class,
        ])->assignRole('User');
    }

    /**
     * Check if farmer can be processed
     */
    private function canProcessFarmer(Farmer $farmer, string $requiredStatus): bool
    {
        return $farmer->lga_id === auth()->user()->administrative_id && 
               $farmer->status === $requiredStatus;
    }

    /**
     * Clear cache
     */
    private function clearFarmerCache($lgaId): void
    {
        Cache::forget("lga_admin_farmers_{$lgaId}");
    }

    /**
     * View farmer credentials (for LGA Admin and Enrollment Agent access)
     */
    public function viewCredentials(Farmer $farmer)
    {
        if ($farmer->lga_id !== auth()->user()->administrative_id) {
            abort(403, 'Unauthorized access.');
        }

        if (!in_array($farmer->status, ['pending_activation', 'active'])) {
            return back()->with('error', 'Credentials are only available for approved farmers.');
        }

        return view('lga_admin.farmers.credentials', compact('farmer'));
    }
}