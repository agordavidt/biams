<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Partner extends Model
{
    use HasFactory;

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
        'is_active'
    ];

    protected $casts = [
        'establishment_date' => 'date',
        'focus_areas' => 'array',
        'is_active' => 'boolean',
    ];

    // Relationships
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
            'ngo' => 'NGO',
            'private_company' => 'Private Company',
            'cooperative' => 'Cooperative',
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
            'agricultural_inputs' => 'Agricultural Inputs',
            'agricultural_technology' => 'Agricultural Technology',
            'agricultural_research' => 'Agricultural Research and Development',
            'agricultural_extension' => 'Agricultural Extension Services and Training',
            'agricultural_finance' => 'Agricultural Finance and Investment',
            'market_access' => 'Market Access and Value Chain Development',
            'sustainable_agriculture' => 'Sustainable Agriculture and Climate-Smart Practices',
            'other' => 'Other'
        ];
    }
}