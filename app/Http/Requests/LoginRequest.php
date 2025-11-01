<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => 'required|email|max:255',
            'password' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'E-posta zorunludur',
            'email.email' => 'E-posta geçerli bir e-posta adresi olmalıdır',
            'email.max' => 'E-posta 255 karakterden az olmalıdır',
            'password.required' => 'Parola zorunludur',
        ];
    }
}
