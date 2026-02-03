<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDiscountRequest;
use App\Models\MonthlyDiscount;
use App\Services\CurrentSchoolYearService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DiscountController extends Controller
{
    public function __construct(
        private CurrentSchoolYearService $currentSchoolYearService,
    ) {
    }

    public function index(Request $request): JsonResponse
    {
        $schoolYear = $this->currentSchoolYearService->current();

        $query = MonthlyDiscount::query()
            ->with('student')
            ->where('school_year_id', $schoolYear->id);

        if ($request->filled('student_id')) {
            $query->where('student_id', (int) $request->input('student_id'));
        }

        if ($request->filled('is_active')) {
            $query->where('is_active', filter_var($request->input('is_active'), FILTER_VALIDATE_BOOLEAN));
        }

        return response()->json($query->latest('id')->get());
    }

    public function store(StoreDiscountRequest $request): JsonResponse
    {
        $schoolYear = $this->currentSchoolYearService->current();

        $discount = MonthlyDiscount::query()->create([
            'student_id' => $request->integer('student_id'),
            'school_year_id' => $schoolYear->id,
            'start_month' => $request->integer('start_month'),
            'end_month' => $request->integer('end_month'),
            'amount' => $request->integer('amount'),
            'reason' => $request->string('reason')->toString(),
            'is_active' => $request->boolean('is_active', true),
        ]);

        return response()->json($discount, 201);
    }

    public function update(StoreDiscountRequest $request, int $id): JsonResponse
    {
        $discount = MonthlyDiscount::query()->findOrFail($id);

        $discount->update([
            'student_id' => $request->integer('student_id'),
            'start_month' => $request->integer('start_month'),
            'end_month' => $request->integer('end_month'),
            'amount' => $request->integer('amount'),
            'reason' => $request->string('reason')->toString(),
            'is_active' => $request->boolean('is_active', true),
        ]);

        return response()->json($discount);
    }

    public function destroy(int $id): JsonResponse
    {
        $discount = MonthlyDiscount::query()->findOrFail($id);
        $discount->update(['is_active' => false]);

        return response()->json(['message' => 'Remise désactivée.']);
    }
}
