<?php

namespace App\Services;

use App\Models\Payment;

class ReceiptService
{
    public function buildReceipt(Payment $payment): array
    {
        $payment->loadMissing(['student', 'schoolYear']);

        return [
            'receipt_no' => $payment->receipt_no,
            'student' => [
                'id' => $payment->student?->id,
                'name' => $payment->student?->full_name,
                'classroom' => $payment->student?->classroom,
            ],
            'month' => $payment->month,
            'year' => $payment->schoolYear?->code,
            'amount_paid' => $payment->amount_paid,
            'method' => $payment->method,
            'reference' => $payment->reference,
            'paid_at' => optional($payment->paid_at)->toDateTimeString(),
        ];
    }
}
