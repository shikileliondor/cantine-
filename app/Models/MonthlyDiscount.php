<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class MonthlyDiscount extends Model
{
    protected $fillable = [
        'student_id',
        'school_year_id',
        'start_month',
        'end_month',
        'amount',
        'is_active',
        'reason',
    ];

    protected $casts = [
        'start_month' => 'integer',
        'end_month' => 'integer',
        'amount' => 'integer',
        'is_active' => 'boolean',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function schoolYear()
    {
        return $this->belongsTo(SchoolYear::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeForMonth(Builder $query, int $month): Builder
    {
        return $query
            ->where('start_month', '<=', $month)
            ->where('end_month', '>=', $month);
    }
}
