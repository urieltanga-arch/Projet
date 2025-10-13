<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

// RegisterRequest.php
class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['required', 'string', 'max:20', 'unique:users'],
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/^(?=.*[A-Z])(?=.*\d).+$/' // Au moins une majuscule et un chiffre
            ],
            'location' => ['nullable', 'string'],
            'referral_code' => ['nullable', 'string', 'exists:users,referral_code'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Le nom est obligatoire',
            'email.required' => 'L\'email est obligatoire',
            'email.email' => 'L\'email doit être valide',
            'email.unique' => 'Cet email est déjà utilisé',
            'phone.required' => 'Le numéro de téléphone est obligatoire',
            'phone.unique' => 'Ce numéro est déjà utilisé',
            'password.required' => 'Le mot de passe est obligatoire',
            'password.min' => 'Le mot de passe doit contenir au moins 8 caractères',
            'password.regex' => 'Le mot de passe doit contenir au moins une majuscule et un chiffre',
            'password.confirmed' => 'Les mots de passe ne correspondent pas',
            'referral_code.exists' => 'Ce code de parrainage n\'existe pas',
        ];
    }
}

// LoginRequest.php
class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'L\'email est obligatoire',
            'email.email' => 'L\'email doit être valide',
            'password.required' => 'Le mot de passe est obligatoire',
        ];
    }
}