<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFarmerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $agentLgaId = auth()->user()->administrativeUnit->id;
        return [
            // Step 1: Personal & Identity
            'nin' => ['required', 'string', 'max:15', 'unique:farmers,nin'],
            'full_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:farmers,email'],
            'phone_primary' => ['required', 'string', 'max:15', 'unique:farmers,phone_primary'],
            'phone_secondary' => ['nullable', 'string', 'max:15'],
            'date_of_birth' => ['required', 'date', 'before:today'],
            'gender' => ['required', 'in:male,female,other'],
            'marital_status' => ['required', 'in:single,married,divorced,widowed'],
             'lga_id' => ['required', 'in:' . $agentLgaId], 
            'ward' => ['required', 'string', 'max:255'],
            'residential_address' => ['required', 'string'],
            'residence_latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'residence_longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'educational_level' => ['required', 'in:none,primary,secondary,tertiary,vocational'],
            'household_size' => ['required', 'integer', 'min:1'],
            'primary_occupation' => ['required', 'in:full_time_farmer,part_time_farmer,civil_servant,trader,artisan,student,other'],
            'other_occupation' => ['required_if:primary_occupation,other', 'nullable', 'string', 'max:255'],
            'cooperative_id' => ['nullable', 'exists:cooperatives,id'],

            // Step 2: Farm & Land Details - FIX FIELD NAME
            'name' => ['required', 'string', 'max:255'], // 
            'farm_type' => ['required', 'in:crops,livestock,fisheries,orchards,forestry'],
            'total_size_hectares' => ['required', 'numeric', 'min:0.01', 'max:99999.9999'],
            'ownership_status' => ['required', 'in:owned,leased,shared,communal'],
            'geolocation_geojson' => ['required', 'string'],

            // Step 3: Practice Details - FIX ORCHARD VALIDATION
            'crop_type' => ['required_if:farm_type,crops', 'nullable', 'string', 'max:255'], // Removed orchards
            'variety' => ['nullable', 'string', 'max:255'],
            'expected_yield_kg' => ['nullable', 'numeric', 'min:0'],
            'farming_method' => ['required_if:farm_type,crops', 'nullable', 'in:irrigation,rain_fed,organic,mixed'],

            'animal_type' => ['required_if:farm_type,livestock', 'nullable', 'string', 'max:255'],
            'herd_flock_size' => ['required_if:farm_type,livestock', 'nullable', 'integer', 'min:1'],
            'breeding_practice' => ['required_if:farm_type,livestock', 'nullable', 'in:open_grazing,ranching,intensive,semi_intensive'],

            'fishing_type' => ['required_if:farm_type,fisheries', 'nullable', 'in:aquaculture_pond,riverine,reservoir'],
            'species_raised' => ['required_if:farm_type,fisheries', 'nullable', 'string', 'max:255'],
            'pond_size_sqm' => ['nullable', 'numeric', 'min:0'],
            'expected_harvest_kg' => ['nullable', 'numeric', 'min:0'],

            'tree_type' => ['required_if:farm_type,orchards', 'nullable', 'string', 'max:255'],
            'number_of_trees' => ['required_if:farm_type,orchards', 'nullable', 'integer', 'min:1'],
            'maturity_stage' => ['required_if:farm_type,orchards', 'nullable', 'in:seedling,immature,producing'],

            // Media uploads (keep as is, but need datase columns)
            'farmer_photo' => ['required', 'image', 'mimes:jpeg,jpg,png', 'max:2048'],
            'farm_photo' => ['required', 'image', 'mimes:jpeg,jpg,png', 'max:2048'],
        
            ];
    }

    public function messages(): array
    {
        return [
            'nin.unique' => 'This NIN is already registered in the system.',
            'email.unique' => 'This email address is already registered.',
            'phone_primary.unique' => 'This phone number is already registered.',
            'date_of_birth.before' => 'Date of birth must be in the past.',
            'other_occupation.required_if' => 'Please specify the other occupation.',
            'crop_type.required_if' => 'Crop type is required for crop farming.',
            'animal_type.required_if' => 'Animal type is required for livestock farming.',
            'fishing_type.required_if' => 'Fishing type is required for fisheries.',
            'tree_type.required_if' => 'Tree type is required for orchards.',
        ];
    }
}
