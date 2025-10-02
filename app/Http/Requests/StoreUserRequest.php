<?php


namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Models\Role;
use App\Models\Department;
use App\Models\Agency;
use App\Models\LGA;

class StoreUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('manage_users');
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $globalRoles = ['Super Admin', 'Governor'];
        
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role_id' => ['required', 'exists:roles,id'],
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

    /**
     * Get custom validation messages.
     */
    public function messages(): array
    {
        return [
            'administrative_type.required' => 'This role requires an administrative unit assignment.',
            'administrative_id.required' => 'Please select a specific administrative unit.',
            'role_id.required' => 'Please select a role for this user.',
            'role_id.exists' => 'The selected role is invalid.',
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $this->validateAdministrativeUnit($validator);
            $this->validateRoleUnitCompatibility($validator);
        });
    }

    /**
     * Validate that the administrative_id exists in the correct table.
     */
    protected function validateAdministrativeUnit($validator): void
    {
        if (!$this->administrative_type || !$this->administrative_id) {
            return;
        }

        $modelClass = "App\\Models\\{$this->administrative_type}";
        
        if (!class_exists($modelClass)) {
            $validator->errors()->add(
                'administrative_type', 
                'Invalid administrative type selected.'
            );
            return;
        }
        
        $exists = $modelClass::where('id', $this->administrative_id)->exists();
        
        if (!$exists) {
            $validator->errors()->add(
                'administrative_id', 
                "The selected {$this->administrative_type} does not exist."
            );
        }
    }

    /**
     * Validate that the role is compatible with the selected administrative unit type.
     */
    protected function validateRoleUnitCompatibility($validator): void
    {
        if (!$this->role_id || !$this->administrative_type) {
            return;
        }

        $role = Role::find($this->role_id);
        
        if (!$role) {
            return;
        }

        // Define which roles can be assigned to which unit types
        $roleUnitMap = [
            'LGA Admin' => ['LGA'],
            'Enrollment Agent' => ['LGA'],
            'State Admin' => ['Department', 'Agency'],
        ];

        if (isset($roleUnitMap[$role->name])) {
            $allowedTypes = $roleUnitMap[$role->name];
            
            if (!in_array($this->administrative_type, $allowedTypes)) {
                $validator->errors()->add(
                    'administrative_type',
                    "The role '{$role->name}' can only be assigned to: " . 
                    implode(' or ', $allowedTypes) . '.'
                );
            }
        }
    }

    /**
     * Get validated data with administrative unit properly formatted.
     */
    public function validated($key = null, $default = null)
    {
        $data = parent::validated($key, $default);
        
        // Format administrative_type to full class name if provided
        if (!empty($data['administrative_type'])) {
            $data['administrative_type'] = "App\\Models\\{$data['administrative_type']}";
        }
        
        return $data;
    }
}