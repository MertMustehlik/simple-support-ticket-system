<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|confirmed|string|min:6|regex:/^(?=.*[a-zA-Z])(?=.*\d).+$/',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Ad zorunludur',
            'name.max' => 'Ad 255 karakterden az olmalıdır',
            'email.required' => 'E-posta zorunludur',
            'email.email' => 'E-posta geçerli bir e-posta adresi olmalıdır',
            'email.max' => 'E-posta 255 karakterden az olmalıdır',
            'email.unique' => 'E-posta benzersiz olmalıdır',
            'password.required' => 'Parola zorunludur',
            'password.min' => 'Parola en az 6 karakter olmalıdır',
            'password.regex' => 'Parola en az bir harf ve bir sayı içermelidir',
            'password.confirmed' => 'Parolalar uyuşmalıdır',
        ];
    }
}
