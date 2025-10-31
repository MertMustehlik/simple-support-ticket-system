<?php

namespace App\Http\Requests\Ticket;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:500',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Başlık zorunlu.',
            'title.string' => 'Başlık metin tipi olmalıdır.',
            'title.max' => 'Başlık 255 karakterden fazla olamaz.',
            'description.required' => 'Açıklama zorunlu.',
            'description.string' => 'Açıklama metin tipi olmalıdır.',
            'description.max' => 'Açıklama 500 karakterden fazla olamaz.',
        ];
    }
}
