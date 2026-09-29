<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLeaveTypeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', 'unique:leave_types,name'],
            'amount_of_days' => ['required', 'integer', 'min:1'],
            'position' => ['nullable', 'integer', 'min:0'],
        ];
    }
}
