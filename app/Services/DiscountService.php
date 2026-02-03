<?php

namespace App\Services;

use App\Models\MonthlyDiscount;

class DiscountService
{
    public function discountForStudentMonth(int $studentId, int $schoolYearId, int $month): int
    {
        return (int) MonthlyDiscount::query()
            ->where('student_id', $studentId)
            ->where('school_year_id', $schoolYearId)
            ->active()
            ->forMonth($month)
            ->sum('amount');
    }
}
