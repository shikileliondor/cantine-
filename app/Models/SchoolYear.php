<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use RuntimeException;

class SchoolYear extends Model
{
    protected $fillable = [
        'code',
        'starts_at',
        'ends_at',
        'is_active',
    ];

    protected $casts = [
        'starts_at' => 'date',
        'ends_at' => 'date',
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

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public static function current(): self
    {
        $year = static::active()->first();

        if (! $year) {
            throw new RuntimeException('No active school year found.');
        }

        return $year;
    }
}
