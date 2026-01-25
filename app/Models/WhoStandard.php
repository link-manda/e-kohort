<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WhoStandard extends Model
{
    use HasFactory;

    protected $fillable = [
        'gender',
        'type',
        'age_month',
        'length_cm',
        'sd_minus_3',
        'sd_minus_2',
        'sd_minus_1',
        'sd_median',
        'sd_plus_1',
        'sd_plus_2',
        'sd_plus_3',
    ];

    protected $casts = [
        'age_month' => 'integer',
        'length_cm' => 'float',
        'sd_minus_3' => 'float',
        'sd_minus_2' => 'float',
        'sd_minus_1' => 'float',
        'sd_median' => 'float',
        'sd_plus_1' => 'float',
        'sd_plus_2' => 'float',
        'sd_plus_3' => 'float',
    ];

    /**
     * Scope untuk filter berdasarkan gender dan type
     */
    public function scopeForGenderAndType($query, string $gender, string $type)
    {
        return $query->where('gender', $gender)->where('type', $type);
    }

    /**
     * Scope untuk mencari standar berdasarkan umur
     */
    public function scopeForAge($query, int $ageMonth)
    {
        return $query->where('age_month', $ageMonth);
    }

    /**
     * Scope untuk mencari standar berdasarkan tinggi badan (BB/TB)
     */
    public function scopeForHeight($query, float $height)
    {
        return $query->where('length_cm', '>=', $height - 0.5)
                     ->where('length_cm', '<=', $height + 0.5);
    }
}
