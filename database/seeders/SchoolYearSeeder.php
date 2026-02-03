<?php

namespace Database\Seeders;

use App\Models\SchoolYear;
use Illuminate\Database\Seeder;

class SchoolYearSeeder extends Seeder
{
    public function run(): void
    {
        if (SchoolYear::query()->where('is_active', true)->exists()) {
            return;
        }

        SchoolYear::query()->create([
            'code' => '2025-2026',
            'is_active' => true,
        ]);
    }
}
