<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreEmployeeLeaveBalanceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'leave_type_id' => [
                'required', 'integer', 'exists:leave_types,id',
                Rule::unique('employee_leave_balances')->where(fn ($query) => $query
                    ->where('employee_id', $this->route('employee')->employee_id)
                    ->where('year', $this->input('year'))),
            ],
            'year' => ['required', 'integer', 'digits:4', 'min:2000', 'max:2100'],
            'balance_in_days' => ['required', 'integer', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'leave_type_id.unique' => 'A balance for this leave type and year already exists for this employee.',
        ];
    }
}
