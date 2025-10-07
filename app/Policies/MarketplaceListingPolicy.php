<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Market\MarketplaceListing;
use App\Models\Market\MarketplaceSubscription;
use Illuminate\Auth\Access\HandlesAuthorization;

class MarketplaceListingPolicy
{
    use HandlesAuthorization;

    /**
     * Determine if the user can view any listings.
     */
    public function viewAny(?User $user)
    {
        // Anyone can view the marketplace (public access)
        return true;
    }

    /**
     * Determine if the user can view the listing.
     */
    public function view(?User $user, MarketplaceListing $listing)
    {
        // Anyone can view active listings
        if ($listing->is_active) {
            return true;
        }

        // Owner can view their own listings regardless of status
        if ($user && $user->id === $listing->user_id) {
            return true;
        }

        // Admins can view any listing
        if ($user && $user->hasAnyRole(['Super Admin', 'State Admin'])) {
            return true;
        }

        return false;
    }

    /**
     * Determine if the user can create listings.
     */
    public function create(User $user)
    {
        // Must be a User role (farmer)
        if (!$user->hasRole('User')) {
            return false;
        }

        // Must have an active subscription
        return $this->hasActiveSubscription($user);
    }

    /**
     * Determine if the user can update the listing.
     */
    public function update(User $user, MarketplaceListing $listing)
    {
        // Must own the listing
        if ($user->id !== $listing->user_id) {
            return false;
        }

        // Must have active subscription
        if (!$this->hasActiveSubscription($user)) {
            return false;
        }

        // Can only edit if draft, rejected, or active
        return in_array($listing->status, ['draft', 'rejected', 'active']);
    }

    /**
     * Determine if the user can delete the listing.
     */
    public function delete(User $user, MarketplaceListing $listing)
    {
        // Owner can delete their own listings
        if ($user->id === $listing->user_id) {
            return true;
        }

        // Admins can delete any listing
        return $user->hasAnyRole(['Super Admin', 'State Admin']);
    }

    /**
     * Determine if the user can approve listings.
     */
    public function approve(User $user)
    {
        return $user->hasAnyRole(['Super Admin', 'State Admin']) 
            && $user->can('manage_supplier_catalog');
    }

    /**
     * Determine if the user can reject listings.
     */
    public function reject(User $user)
    {
        return $user->hasAnyRole(['Super Admin', 'State Admin']) 
            && $user->can('manage_supplier_catalog');
    }

    /**
     * Determine if the user can view inquiries for a listing.
     */
    public function viewInquiries(User $user, MarketplaceListing $listing)
    {
        // Owner can view inquiries for their listings
        if ($user->id === $listing->user_id) {
            return true;
        }

        // Admins can view all inquiries
        return $user->hasAnyRole(['Super Admin', 'State Admin']);
    }

    /**
     * Check if user has an active marketplace subscription.
     */
    private function hasActiveSubscription(User $user): bool
    {
        return MarketplaceSubscription::where('user_id', $user->id)
            ->active()
            ->exists();
    }

    /**
     * Determine if the user can submit a listing for review.
     */
    public function submit(User $user, MarketplaceListing $listing)
    {
        return $user->id === $listing->user_id 
            && in_array($listing->status, ['draft', 'rejected'])
            && $this->hasActiveSubscription($user);
    }

    /**
     * Determine if the user can resubmit a rejected listing.
     */
    public function resubmit(User $user, MarketplaceListing $listing)
    {
        return $user->id === $listing->user_id 
            && $listing->status === 'rejected'
            && $this->hasActiveSubscription($user);
    }
}