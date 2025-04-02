<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resource extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'description', 'price', 'requires_payment', 'payment_option',
        'bank_account_name', 'bank_account_number', 'bank_name',
        'entrasact_instruction', 'paystack_instruction', 'form_fields',
        'target_practice', 'is_active',
    ];

    protected $casts = [
        'requires_payment' => 'boolean',
        'is_active' => 'boolean',
        'form_fields' => 'array',
    ];

    public function applications()
    {
        return $this->hasMany(ResourceApplication::class);
    }

    public function isAvailableFor($userPractice): bool
    {
        return $this->target_practice === 'all' || $this->target_practice === $userPractice;
    }

    public function getPaymentInstructions(): array
    {
        return match ($this->payment_option) {
            'bank_transfer' => [
                'account_name' => $this->bank_account_name,
                'account_number' => $this->bank_account_number,
                'bank_name' => $this->bank_name,
            ],
            'entrasact' => ['instruction' => $this->entrasact_instruction],
            'paystack' => ['instruction' => $this->paystack_instruction],
            default => [],
        };
    }
}