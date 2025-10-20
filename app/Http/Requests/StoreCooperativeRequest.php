<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCooperativeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->hasRole('LGA Admin');
    }

    public function rules(): array
    {
        return [
            'registration_number' => ['required', 'string', 'max:100', 'unique:cooperatives,registration_number'],
            'name' => ['required', 'string', 'max:255'],
            'contact_person' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:20', 'regex:/^[0-9+\-\(\)\s]+$/'],
            'email' => ['nullable', 'email', 'max:255'],
            'total_member_count' => ['nullable', 'integer', 'min:0', 'max:100000'],
            'total_land_size' => ['nullable', 'numeric', 'min:0', 'max:1000000'],
            'primary_activities' => ['required', 'array', 'min:1'],
            'primary_activities.*' => ['required', 'string', 'max:100'],
        ];
    }

    public function messages(): array
    {
        return [
            'registration_number.required' => 'Registration number is required.',
            'registration_number.unique' => 'This registration number is already in use.',
            'name.required' => 'Cooperative name is required.',
            'contact_person.required' => 'Contact person name is required.',
            'phone.required' => 'Phone number is required.',
            'phone.regex' => 'Please enter a valid phone number.',
            'primary_activities.required' => 'Please select at least one primary activity.',
            'primary_activities.min' => 'Please select at least one primary activity.',
        ];
    }

    protected function prepareForValidation(): void
    {
        // Clean phone number
        if ($this->has('phone')) {
            $this->merge([
                'phone' => preg_replace('/[^0-9+]/', '', $this->phone)
            ]);
        }

        // Ensure total_member_count is integer or null
        if ($this->has('total_member_count') && $this->total_member_count === '') {
            $this->merge(['total_member_count' => 0]);
        }
    }
}

