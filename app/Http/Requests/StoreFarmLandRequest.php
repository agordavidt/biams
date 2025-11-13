<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFarmLandRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // Farm Land Details
            'name' => ['required', 'string', 'max:255'],
            'farm_type' => ['required', 'in:crops,livestock,fisheries,orchards,forestry'],
            'total_size_hectares' => ['required', 'numeric', 'min:0.01', 'max:99999.9999'],
            'ownership_status' => ['required', 'in:owned,leased,shared,communal'],
            'geolocation_geojson' => ['required', 'string'],

            // Practice Details - Crops
            'crop_type' => ['required_if:farm_type,crops', 'nullable', 'string', 'max:255'],
            'variety' => ['nullable', 'string', 'max:255'],
            'expected_yield_kg' => ['nullable', 'numeric', 'min:0'],
            'farming_method' => ['required_if:farm_type,crops', 'nullable', 'in:irrigation,rain_fed,organic,mixed'],

            // Practice Details - Livestock
            'animal_type' => ['required_if:farm_type,livestock', 'nullable', 'string', 'max:255'],
            'herd_flock_size' => ['required_if:farm_type,livestock', 'nullable', 'integer', 'min:1'],
            'breeding_practice' => ['required_if:farm_type,livestock', 'nullable', 'in:open_grazing,ranching,intensive,semi_intensive'],

            // Practice Details - Fisheries
            'fishing_type' => ['required_if:farm_type,fisheries', 'nullable', 'in:aquaculture_pond,riverine,reservoir'],
            'species_raised' => ['required_if:farm_type,fisheries', 'nullable', 'string', 'max:255'],
            'pond_size_sqm' => ['nullable', 'numeric', 'min:0'],
            'expected_harvest_kg' => ['nullable', 'numeric', 'min:0'],

            // Practice Details - Orchards
            'tree_type' => ['required_if:farm_type,orchards', 'nullable', 'string', 'max:255'],
            'number_of_trees' => ['required_if:farm_type,orchards', 'nullable', 'integer', 'min:1'],
            'maturity_stage' => ['required_if:farm_type,orchards', 'nullable', 'in:seedling,immature,producing'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Farm name/reference is required.',
            'farm_type.required' => 'Farm type is required.',
            'total_size_hectares.required' => 'Farm size is required.',
            'total_size_hectares.min' => 'Farm size must be at least 0.01 hectares.',
            'ownership_status.required' => 'Ownership status is required.',
            'geolocation_geojson.required' => 'Farm boundaries (GeoJSON) are required.',
            
            'crop_type.required_if' => 'Crop type is required for crop farming.',
            'farming_method.required_if' => 'Farming method is required for crop farming.',
            
            'animal_type.required_if' => 'Animal type is required for livestock farming.',
            'herd_flock_size.required_if' => 'Herd/flock size is required for livestock farming.',
            'breeding_practice.required_if' => 'Breeding practice is required for livestock farming.',
            
            'fishing_type.required_if' => 'Fishing type is required for fisheries.',
            'species_raised.required_if' => 'Species raised is required for fisheries.',
            
            'tree_type.required_if' => 'Tree type is required for orchards.',
            'number_of_trees.required_if' => 'Number of trees is required for orchards.',
            'maturity_stage.required_if' => 'Maturity stage is required for orchards.',
        ];
    }
}