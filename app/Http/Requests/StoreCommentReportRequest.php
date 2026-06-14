<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCommentReportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'reason' => ['required', 'string', 'min:10', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'reason.required' => 'Merci d\'expliquer pourquoi tu signales ce commentaire.',
            'reason.min' => 'Le motif doit contenir au moins :min caractères.',
            'reason.max' => 'Le motif ne peut pas dépasser :max caractères.',
        ];
    }
}
