<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class UpdateUserRequest extends StoreUserRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $globalRoles = ['Super Admin', 'Governor'];
        $user = $this->route('user');
        
        $rules = [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id)
            ],
            'password' => 'nullable|string|min:8|confirmed',
            'role_id' => ['required', 'exists:roles,id'],
            'status' => ['required', Rule::in(['onboarded', 'pending', 'rejected'])],
        ];
        
        // Check if role requires administrative unit
        if ($this->role_id) {
            $role = Role::find($this->role_id);
            
            if ($role && !in_array($role->name, $globalRoles)) {
                $rules['administrative_type'] = [
                    'required',
                    'string',
                    Rule::in(['Department', 'Agency', 'LGA'])
                ];
                $rules['administrative_id'] = 'required|integer';
            }
        }
        
        return $rules;
    }
}