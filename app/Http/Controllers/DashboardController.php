<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Student;
use App\Services\CurrentSchoolYearService;
use App\Services\PaymentStatusService;
use Carbon\Carbon;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(
        private CurrentSchoolYearService $currentSchoolYearService,
        private PaymentStatusService $paymentStatusService,
    ) {
    }

    public function index(): View
    {
        $schoolYear = $this->currentSchoolYearService->current();
        $now = Carbon::now();
        $month = (int) $now->month;
        $monthLabel = $now->locale('fr')->translatedFormat('F');

        $students = Student::query()->active()->get(['id']);
        $totalStudents = $students->count();

        $totals = [
            'paid' => 0,
            'partial' => 0,
            'unpaid' => 0,
        ];

        foreach ($students as $student) {
            $status = $this->paymentStatusService->statusForStudentMonth($student->id, $schoolYear->id, $month);
            $totals[$status] = ($totals[$status] ?? 0) + 1;
        }

        $totalCollected = (int) Payment::query()
            ->forYear($schoolYear->id)
            ->forMonth($month)
            ->sum('amount_paid');

        $recentPayments = Payment::query()
            ->with('student')
            ->forYear($schoolYear->id)
            ->orderByDesc('paid_at')
            ->orderByDesc('id')
            ->take(10)
            ->get();

        return view('dashboard', [
            'schoolYear' => $schoolYear,
            'now' => $now,
            'month' => $month,
            'monthLabel' => $monthLabel,
            'totalStudents' => $totalStudents,
            'totalCollected' => $totalCollected,
            'totalPaid' => $totals['paid'],
            'totalPartial' => $totals['partial'],
            'totalUnpaid' => $totals['unpaid'],
            'recentPayments' => $recentPayments,
        ]);
    }
}
