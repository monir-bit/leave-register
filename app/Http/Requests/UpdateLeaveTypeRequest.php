<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateLeaveTypeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required', 'string', 'max:255',
                Rule::unique('leave_types', 'name')->ignore($this->route('leave_type')),
            ],
            'amount_of_days' => ['required', 'integer', 'min:1'],
        ];
    }
}
