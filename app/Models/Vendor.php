<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Vendor extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'legal_name',
        'registration_number',
        'organization_type',
        'establishment_date',
        'contact_person_name',
        'contact_person_title',
        'contact_person_phone',
        'contact_person_email',
        'address',
        'website',
        'description',
        'focus_areas',
        'tax_identification_number',
        'registration_certificate',
        'bank_name',
        'bank_account_name',
        'bank_account_number',
        'is_active',
        'registered_by'
    ];

    protected $casts = [
        'establishment_date' => 'date',
        'focus_areas' => 'array',
        'is_active' => 'boolean',
    ];

    // Relationships
    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'vendor_id');
    }

    public function vendorManager(): HasMany
    {
        return $this->users()->whereHas('roles', function($query) {
            $query->where('name', 'Vendor Manager');
        });
    }

    public function distributionAgents(): HasMany
    {
        return $this->users()->whereHas('roles', function($query) {
            $query->where('name', 'Distribution Agent');
        });
    }

    public function registeredBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'registered_by');
    }

    public function resources(): HasMany
    {
        return $this->hasMany(Resource::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Helper Methods
    public function getOrganizationTypeOptions(): array
    {
        return [
            'private_company' => 'Private Company',
            'cooperative' => 'Cooperative',
            'ngo' => 'NGO',
            'research_institution' => 'Research Institution',
            'international_organization' => 'International Organization',
            'government' => 'Government Agency',
            'other' => 'Other'
        ];
    }

    public function getFocusAreaOptions(): array
    {
        return [
            'crop_production' => 'Crop Production',
            'livestock' => 'Livestock',
            'agricultural_processing' => 'Agricultural Processing',
            'agricultural_inputs' => 'Agricultural Inputs (Seeds, Fertilizers)',
            'agricultural_technology' => 'Agricultural Technology',
            'agricultural_extension' => 'Extension Services and Training',
            'agricultural_finance' => 'Agricultural Finance',
            'market_access' => 'Market Access and Value Chain',
            'sustainable_agriculture' => 'Sustainable Agriculture',
            'other' => 'Other'
        ];
    }
}