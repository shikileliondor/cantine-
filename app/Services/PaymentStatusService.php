<?php

namespace App\Services;

use App\Models\Payment;

class PaymentStatusService
{
    public function __construct(
        private FeeService $feeService,
        private DiscountService $discountService,
    ) {
    }

    public function dueForStudentMonth(int $studentId, int $schoolYearId, int $month): int
    {
        $tariff = $this->feeService->monthlyFee();
        $discount = $this->discountService->discountForStudentMonth($studentId, $schoolYearId, $month);

        return max(0, $tariff - $discount);
    }

    public function paidForStudentMonth(int $studentId, int $schoolYearId, int $month): int
    {
        return (int) Payment::query()
            ->forStudent($studentId)
            ->forYear($schoolYearId)
            ->forMonth($month)
            ->sum('amount_paid');
    }

    public function remainingForStudentMonth(int $studentId, int $schoolYearId, int $month): int
    {
        $due = $this->dueForStudentMonth($studentId, $schoolYearId, $month);
        $paid = $this->paidForStudentMonth($studentId, $schoolYearId, $month);

        return max(0, $due - $paid);
    }

    public function statusForStudentMonth(int $studentId, int $schoolYearId, int $month): string
    {
        $due = $this->dueForStudentMonth($studentId, $schoolYearId, $month);
        $paid = $this->paidForStudentMonth($studentId, $schoolYearId, $month);

        if ($paid >= $due) {
            return 'paid';
        }

        if ($paid > 0) {
            return 'partial';
        }

        return 'unpaid';
    }
}
