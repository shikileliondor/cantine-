<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('receipt_no')->unique()->nullable();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('school_year_id')->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('month');
            $table->unsignedBigInteger('amount_paid');
            $table->string('method')->default('cash');
            $table->string('reference')->nullable();
            $table->text('note')->nullable();
            $table->dateTime('paid_at')->useCurrent();
            $table->timestamps();

            $table->index(['school_year_id', 'month']);
            $table->index(['student_id', 'school_year_id', 'month']);
            $table->index('paid_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
