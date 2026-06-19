<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use App\Rules\AllowedUsername;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if ($this->filled('username')) {
            $this->merge([
                'username' => strtolower((string) $this->input('username')),
            ]);
        }
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'username' => [
                'required',
                'string',
                'min:3',
                'max:50',
                'regex:/^[a-z0-9_]+$/',
                Rule::unique(User::class, 'username'),
                new AllowedUsername,
            ],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)],
            'password' => ['required', 'confirmed', Password::defaults()],
        ];
    }

    public function messages(): array
    {
        return [
            'username.required' => 'Choisis un pseudo pour commenter sur le blog.',
            'username.min' => 'Le pseudo doit contenir au moins :min caractères.',
            'username.max' => 'Le pseudo ne peut pas dépasser :max caractères.',
            'username.regex' => 'Le pseudo ne peut contenir que des lettres minuscules, des chiffres et des underscores (ex. : kofi_mensah).',
            'username.unique' => 'Ce pseudo est déjà pris. Essaie une autre variante.',
        ];
    }
}
