<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
use App\Models\Student;
use App\Services\CurrentSchoolYearService;
use App\Services\PaymentStatusService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class StudentController extends Controller
{
    public function __construct(
        private CurrentSchoolYearService $currentSchoolYearService,
        private PaymentStatusService $paymentStatusService,
    ) {
    }

    public function index(Request $request): JsonResponse|Response
    {
        if (! $request->expectsJson()) {
            return redirect()->route('dashboard');
        }

        $students = Student::query()
            ->latest('id')
            ->get();

        return response()->json($students);
    }

    public function store(StoreStudentRequest $request): JsonResponse
    {
        $student = Student::query()->create($request->validated());

        return response()->json($student, 201);
    }

    public function update(UpdateStudentRequest $request, int $id): JsonResponse
    {
        $student = Student::query()->findOrFail($id);
        $student->update($request->validated());

        return response()->json($student);
    }

    public function destroy(int $id): JsonResponse
    {
        $student = Student::query()->findOrFail($id);
        $student->delete();

        return response()->json(['message' => 'Étudiant désactivé.']);
    }

    public function show(int $id): JsonResponse
    {
        $student = Student::query()->findOrFail($id);
        $schoolYear = $this->currentSchoolYearService->current();
        $month = (int) now()->month;

        $due = $this->paymentStatusService->dueForStudentMonth($student->id, $schoolYear->id, $month);
        $paid = $this->paymentStatusService->paidForStudentMonth($student->id, $schoolYear->id, $month);
        $remaining = $this->paymentStatusService->remainingForStudentMonth($student->id, $schoolYear->id, $month);
        $status = $this->paymentStatusService->statusForStudentMonth($student->id, $schoolYear->id, $month);

        $history = [];
        foreach (range(1, 12) as $monthIndex) {
            $history[] = [
                'month' => $monthIndex,
                'due' => $this->paymentStatusService->dueForStudentMonth($student->id, $schoolYear->id, $monthIndex),
                'paid' => $this->paymentStatusService->paidForStudentMonth($student->id, $schoolYear->id, $monthIndex),
                'remaining' => $this->paymentStatusService->remainingForStudentMonth($student->id, $schoolYear->id, $monthIndex),
                'status' => $this->paymentStatusService->statusForStudentMonth($student->id, $schoolYear->id, $monthIndex),
            ];
        }

        return response()->json([
            'student' => $student,
            'current_month' => [
                'month' => $month,
                'due' => $due,
                'paid' => $paid,
                'remaining' => $remaining,
                'status' => $status,
            ],
            'history' => $history,
        ]);
    }
}
