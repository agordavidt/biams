<?php

namespace App\Http\Controllers\LGAAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Farmer;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB; // Added DB use statement for clarity

class FarmerReviewController extends Controller
{
    /**
     * Display a listing of farmers pending review and those already reviewed in the admin's LGA.
     */
    public function index()
    {
        $lgaId = auth()->user()->administrative_id;
        
        // 1. Load farmers pending review (Paginated)
        $pendingFarmers = Farmer::forLGA($lgaId)
            ->pendingReview()
            ->with('enrolledBy')
            ->orderBy('created_at', 'asc')
            ->paginate(15, ['*'], 'pending_page');

        // 2. Load rejected/approved farmers for tracking (Paginated, only if needed for a large table, otherwise count is fine)
        $reviewedFarmers = Farmer::forLGA($lgaId)
            ->whereIn('status', ['rejected', 'pending_activation', 'active'])
            ->with('enrolledBy')
            ->orderBy('approved_at', 'desc')
            ->paginate(15, ['*'], 'reviewed_page');

        // 3. Calculate Counts for dashboard cards (The missing logic)
        $baseQuery = Farmer::forLGA($lgaId);

        $rejectedCount = (clone $baseQuery)->where('status', 'rejected')->count();
        $activeCount = (clone $baseQuery)->where('status', 'active')->count();

        // 4. Pass all data to the view
        return view('lga_admin.farmers.index', compact('pendingFarmers', 'reviewedFarmers', 'rejectedCount', 'activeCount'));
    }

    /**
     * Display the specified farmer profile for detailed review.
     */
    public function show(Farmer $farmer)
    {
        // Enforce boundary check: Admin can only review farmers in their LGA
        if ($farmer->lga_id !== auth()->user()->administrative_id) {
            abort(403, 'Unauthorized access to farmer outside your LGA.');
        }

        $farmer->load('enrolledBy', 'approvedBy', 'cooperative', 'farmLands.practiceDetails');

        return view('lga_admin.farmers.show', compact('farmer'));
    }

    /**
     * Approve the farmer enrollment.
     */
    public function approve(Farmer $farmer)
    {
        if ($farmer->lga_id !== auth()->user()->administrative_id || $farmer->status !== 'pending_lga_review') {
            return back()->with('error', 'Enrollment is not eligible for approval.');
        }
        
        $admin = auth()->user();

        if ($farmer->approve($admin)) {
            return back()->with('success', 'Farmer profile approved. Status changed to Pending Activation.');
        }

        return back()->with('error', 'Failed to approve profile.');
    }

    /**
     * Reject the farmer enrollment.
     */
    public function reject(Request $request, Farmer $farmer)
    {
        $request->validate(['rejection_reason' => ['required', 'string', 'min:10']]);

        if ($farmer->lga_id !== auth()->user()->administrative_id || $farmer->status !== 'pending_lga_review') {
            return back()->with('error', 'Enrollment is not eligible for rejection.');
        }
        
        $admin = auth()->user();
        
        if ($farmer->reject($admin, $request->rejection_reason)) {
            return back()->with('success', 'Farmer profile rejected. Enrollment Officer can now resubmit.');
        }

        return back()->with('error', 'Failed to reject profile.');
    }

    /**
     * Activate the farmer account: creates the User record and links it to the Farmer.
     */
    public function activate(Farmer $farmer)
    {
        if ($farmer->status !== 'pending_activation') {
            return back()->with('error', 'Profile must be Approved (Pending Activation) before final activation.');
        }

        try {
            DB::beginTransaction();

            // 1. Create the User Account
            $user = User::create([
                'name' => $farmer->full_name,
                'email' => $farmer->email,
                'phone_number' => $farmer->phone_primary,
                // Hashing the initial password for the User table
                'password' => Hash::make($farmer->initial_password), 
                'email_verified_at' => now(), 
                'status' => 'active', 
                'administrative_id' => $farmer->lga_id, 
                'administrative_type' => \App\Models\LGA::class, 
            ]);
            
            // 2. Assign the 'User' Role
            $user->assignRole('User');

            // 3. Link and Activate Farmer Profile
            $farmer->activate($user);

            // 4. Send Notification (Optional, but recommended)
            // \App\Services\SmsService::sendActivationPin($farmer->phone_primary, $farmer->initial_password);

            DB::commit();

            return back()->with('success', 'Farmer account successfully activated. User account created and credentials notified.');

        } catch (\Exception $e) {
            DB::rollBack();
            // Handle unique constraint violation if email/phone already existed
            if ($e instanceof ValidationException) throw $e;
            return back()->with('error', 'Activation failed: ' . $e->getMessage());
        }
    }
}