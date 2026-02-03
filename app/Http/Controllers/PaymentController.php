<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePaymentRequest;
use App\Models\Payment;
use App\Services\CurrentSchoolYearService;
use App\Services\ReceiptService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function __construct(
        private CurrentSchoolYearService $currentSchoolYearService,
        private ReceiptService $receiptService,
    ) {
    }

    public function index(Request $request): JsonResponse
    {
        $schoolYear = $this->currentSchoolYearService->current();

        $query = Payment::query()->with('student')->forYear($schoolYear->id);

        if ($request->filled('date')) {
            $query->whereDate('paid_at', $request->string('date')->toString());
        }

        if ($request->filled('month')) {
            $query->forMonth((int) $request->input('month'));
        }

        if ($request->filled('classroom')) {
            $classroom = (string) $request->input('classroom');
            $query->whereHas('student', fn ($builder) => $builder->where('classroom', $classroom));
        }

        $payments = $query->orderByDesc('paid_at')->orderByDesc('id')->get();

        return response()->json($payments);
    }

    public function store(StorePaymentRequest $request): JsonResponse
    {
        $schoolYear = $this->currentSchoolYearService->current();

        $payment = Payment::query()->create([
            'student_id' => $request->integer('student_id'),
            'school_year_id' => $schoolYear->id,
            'month' => $request->integer('month'),
            'amount_paid' => $request->integer('amount_paid'),
            'method' => $request->string('method')->toString(),
            'reference' => $request->input('reference'),
            'paid_at' => now(),
        ]);

        return response()->json([
            'payment' => $payment,
            'receipt' => $this->receiptService->buildReceipt($payment),
        ], 201);
    }
}
