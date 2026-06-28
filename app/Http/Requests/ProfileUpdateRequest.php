<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // MOCK API: removed unique database checks
        return [
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:100'],
            'nim' => ['nullable', 'string', 'max:20'],
            'semester' => ['nullable', 'integer', 'min:1', 'max:14'],
            'kelas' => ['nullable', 'string', 'max:5'],
        ];
    }
}
