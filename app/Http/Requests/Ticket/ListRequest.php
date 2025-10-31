<?php

namespace App\Http\Requests\Ticket;

use Illuminate\Foundation\Http\FormRequest;

class ListRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'per_page' => 'nullable|integer|max:1000',
            'page' => 'nullable|integer',
        ];
    }

    public function messages(): array
    {
        return [
            'per_page.integer' => "Sayfa başına gösterilecek öğe sayısı sayısal bir değer olmalıdır.",
            'per_page.max' => "Sayfa başına gösterilecek öğe sayısı 1000'den fazla olamaz.",
            'page.integer' => "Sayfa numarası sayısal bir değer olmalıdır."
        ];
    }
}
