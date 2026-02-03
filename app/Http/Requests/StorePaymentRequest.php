<?php

namespace App\Http\Requests;

use App\Services\CurrentSchoolYearService;
use App\Services\PaymentStatusService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class StorePaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'student_id' => ['required', 'integer', 'exists:students,id'],
            'month' => ['required', 'integer', 'between:1,12'],
            'amount_paid' => ['required', 'integer', 'min:1'],
            'method' => ['required', 'string', 'in:cash,mobile_money'],
            'reference' => ['nullable', 'string', 'max:191'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            if ($validator->errors()->isNotEmpty()) {
                return;
            }

            $studentId = (int) $this->input('student_id');
            $month = (int) $this->input('month');
            $amount = (int) $this->input('amount_paid');

            $year = app(CurrentSchoolYearService::class)->current();
            $remaining = app(PaymentStatusService::class)
                ->remainingForStudentMonth($studentId, $year->id, $month);

            if ($amount > $remaining) {
                $validator->errors()->add('amount_paid', 'Montant dépasse le reste à payer');
            }
        });
    }
}
