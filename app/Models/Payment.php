<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'receipt_no',
        'student_id',
        'school_year_id',
        'month',
        'amount_paid',
        'method',
        'reference',
        'note',
        'paid_at',
    ];

    protected $casts = [
        'month' => 'integer',
        'amount_paid' => 'integer',
        'paid_at' => 'datetime',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function schoolYear()
    {
        return $this->belongsTo(SchoolYear::class);
    }

    public function scopeForYear(Builder $query, int $id): Builder
    {
        return $query->where('school_year_id', $id);
    }

    public function scopeForMonth(Builder $query, int $month): Builder
    {
        return $query->where('month', $month);
    }

    public function scopeForStudent(Builder $query, int $studentId): Builder
    {
        return $query->where('student_id', $studentId);
    }

    protected static function booted(): void
    {
        static::created(function (self $payment): void {
            if ($payment->receipt_no !== null) {
                return;
            }

            $payment->loadMissing('schoolYear');

            if (! $payment->schoolYear) {
                return;
            }

            $payment->receipt_no = 'CANT-'.$payment->schoolYear->code.'-'.str_pad((string) $payment->id, 6, '0', STR_PAD_LEFT);
            $payment->saveQuietly();
        });
    }
}
