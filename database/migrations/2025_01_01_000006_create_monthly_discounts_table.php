<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('monthly_discounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('school_year_id')->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('start_month');
            $table->unsignedTinyInteger('end_month');
            $table->unsignedBigInteger('amount');
            $table->boolean('is_active')->default(true)->index();
            $table->string('reason')->nullable();
            $table->timestamps();

            $table->index(['student_id', 'school_year_id']);
            $table->index(['school_year_id', 'start_month', 'end_month']);
            $table->index(['student_id', 'start_month', 'end_month']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('monthly_discounts');
    }
};
