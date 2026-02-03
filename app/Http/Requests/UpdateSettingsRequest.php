<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSettingsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'monthly_fee_amount' => ['sometimes', 'required', 'integer', 'min:0'],
            'payment_methods' => ['sometimes', 'required', 'array'],
            'payment_methods.*' => ['string', 'in:cash,mobile_money'],
            'active_school_year_code' => ['sometimes', 'nullable', 'string'],
            'school_year_id' => ['sometimes', 'nullable', 'integer', 'exists:school_years,id'],
        ];
    }
}
