<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vaccine extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'code',
        'name',
        'description',
        'min_age_months',
        'max_age_months',
        'sort_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'min_age_months' => 'integer',
            'max_age_months' => 'integer',
            'sort_order' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Scope untuk hanya vaksin yang aktif.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope untuk mengurutkan berdasarkan sort_order.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }

    /**
     * Check if vaccine is appropriate for given age in months.
     */
    public function isAgeAppropriate(int $ageInMonths): array
    {
        if ($ageInMonths < $this->min_age_months) {
            return [
                'appropriate' => false,
                'status' => 'too_young',
                'message' => "Anak belum cukup umur untuk {$this->name} (min {$this->min_age_months} bulan)",
            ];
        }

        if ($ageInMonths > $this->max_age_months) {
            return [
                'appropriate' => false,
                'status' => 'late',
                'message' => "Pemberian {$this->name} terlambat (seharusnya max {$this->max_age_months} bulan)",
            ];
        }

        return [
            'appropriate' => true,
            'status' => 'appropriate',
            'message' => "Umur sesuai untuk {$this->name} ({$this->min_age_months}-{$this->max_age_months} bulan)",
        ];
    }

    /**
     * Get formatted age range.
     */
    public function getAgeRangeAttribute(): string
    {
        return "{$this->min_age_months}-{$this->max_age_months} bulan";
    }
}
