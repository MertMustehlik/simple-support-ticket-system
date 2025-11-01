<?php

namespace App\Http\Requests\Ticket;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Ticket;

class UpdateStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => 'required|in:' . implode(',', Ticket::getStatuses()),
        ];
    }

    public function messages(): array
    {
        return [
            'status.required' => 'Durum zorunlu',
            'status.in' => 'Geçersiz durum',
        ];
    }
}
