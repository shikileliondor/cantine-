<?php

namespace App\Http\Requests;

use App\Services\FeeService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class StoreDiscountRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'student_id' => ['required', 'integer', 'exists:students,id'],
            'start_month' => ['required', 'integer', 'between:1,12'],
            'end_month' => ['required', 'integer', 'between:1,12'],
            'amount' => ['required', 'integer', 'min:0'],
            'reason' => ['nullable', 'string', 'max:191'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            if ($validator->errors()->isNotEmpty()) {
                return;
            }

            $start = (int) $this->input('start_month');
            $end = (int) $this->input('end_month');

            if ($start > $end) {
                $validator->errors()->add('start_month', 'Le mois de début doit être inférieur ou égal au mois de fin.');
            }

            $amount = (int) $this->input('amount');
            $fee = app(FeeService::class)->monthlyFee();

            if ($amount > $fee) {
                $validator->errors()->add('amount', 'La remise ne peut pas dépasser le tarif mensuel.');
            }
        });
    }
}
