<?php

namespace Database\Seeders;

use App\Models\Student;
use Illuminate\Database\Seeder;

class StudentSeeder extends Seeder
{
    public function run(): void
    {
        $students = [
            [
                'matricule' => 'MAT-001',
                'last_name' => 'Traore',
                'first_name' => 'Aminata',
                'classroom' => 'CP1',
                'parent_phone' => '70000001',
            ],
            [
                'matricule' => 'MAT-002',
                'last_name' => 'Diallo',
                'first_name' => 'Moussa',
                'classroom' => 'CP2',
                'parent_phone' => '70000002',
            ],
            [
                'matricule' => 'MAT-003',
                'last_name' => 'Kone',
                'first_name' => 'Fatou',
                'classroom' => 'CE1',
                'parent_phone' => '70000003',
            ],
        ];

        foreach ($students as $student) {
            Student::query()->firstOrCreate(
                ['matricule' => $student['matricule']],
                $student
            );
        }
    }
}
