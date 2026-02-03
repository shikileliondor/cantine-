<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'matricule',
        'last_name',
        'first_name',
        'classroom',
        'parent_phone',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function discounts()
    {
        return $this->hasMany(MonthlyDiscount::class);
    }

    public function getFullNameAttribute(): string
    {
        return trim($this->last_name.' '.$this->first_name);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeByClassroom(Builder $query, string $classroom): Builder
    {
        return $query->where('classroom', $classroom);
    }

    public function paidSumForMonth(int $schoolYearId, int $month): int
    {
        return (int) $this->payments()
            ->where('school_year_id', $schoolYearId)
            ->where('month', $month)
            ->sum('amount_paid');
    }
}
