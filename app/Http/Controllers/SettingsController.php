<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateSettingsRequest;
use App\Models\SchoolYear;
use App\Models\Setting;
use App\Services\CurrentSchoolYearService;
use Illuminate\Http\JsonResponse;

class SettingsController extends Controller
{
    public function __construct(
        private CurrentSchoolYearService $currentSchoolYearService,
    ) {
    }

    public function show(): JsonResponse
    {
        $schoolYear = $this->currentSchoolYearService->current();

        return response()->json([
            'monthly_fee_amount' => Setting::getValue('monthly_fee_amount', 0),
            'payment_methods' => Setting::getValue('payment_methods', []),
            'active_school_year' => [
                'id' => $schoolYear->id,
                'code' => $schoolYear->code,
            ],
        ]);
    }

    public function update(UpdateSettingsRequest $request): JsonResponse
    {
        if ($request->filled('monthly_fee_amount')) {
            Setting::setValue('monthly_fee_amount', $request->integer('monthly_fee_amount'), 'int');
        }

        if ($request->filled('payment_methods')) {
            Setting::setValue('payment_methods', $request->input('payment_methods', []), 'json');
        }

        $schoolYearId = $request->input('school_year_id');
        $schoolYearCode = $request->input('active_school_year_code');

        if ($schoolYearId || $schoolYearCode) {
            SchoolYear::query()->update(['is_active' => false]);

            $target = $schoolYearId
                ? SchoolYear::query()->findOrFail($schoolYearId)
                : SchoolYear::query()->where('code', $schoolYearCode)->firstOrFail();

            $target->update(['is_active' => true]);
        }

        return $this->show();
    }
}
