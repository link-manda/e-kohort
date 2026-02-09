<?php

namespace App\Models;

use App\Traits\GeneratesChildRm;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Child extends Model
{
    use SoftDeletes, GeneratesChildRm;

    protected $fillable = [
        'patient_id',       // Nullable - for internal births (mother registered)
        'parent_name',      // For external births
        'parent_phone',     // For external births
        'parent_address',   // For external births (optional)
        'birth_location',   // 'internal' or 'external'
        'nik',
        'no_rm',
        'name',
        'gender',
        'dob',
        'pob',
        'birth_weight',
        'birth_height',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'dob' => 'date',
            'birth_weight' => 'float',
            'birth_height' => 'float',
        ];
    }

    /**
     * Check if this child is from external birth (mother not registered)
     */
    public function isExternalBirth(): bool
    {
        return $this->birth_location === 'external' || $this->patient_id === null;
    }

    /**
     * Get parent display name - works for both internal and external births
     */
    public function getParentDisplayNameAttribute(): string
    {
        if ($this->patient_id && $this->patient) {
            return $this->patient->name;
        }
        return $this->parent_name ?? 'Tidak Diketahui';
    }

    /**
     * Get parent phone - works for both internal and external births
     */
    public function getParentDisplayPhoneAttribute(): string
    {
        if ($this->patient_id && $this->patient) {
            return $this->patient->phone ?? '-';
        }
        return $this->parent_phone ?? '-';
    }

    /**
     * Get parent address - works for both internal and external births
     */
    public function getParentDisplayAddressAttribute(): string
    {
        if ($this->patient_id && $this->patient) {
            return $this->patient->address ?? '-';
        }
        return $this->parent_address ?? '-';
    }

    /**
     * Get the mother (patient) that owns the child.
     */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    /**
     * Get the delivery record associated with this child's birth.
     * Note: Only works for internal births where patient_id is set.
     */
    public function deliveryRecord(): HasOne
    {
        return $this->hasOne(DeliveryRecord::class, 'pregnancy_id', 'patient_id')
            ->whereDate('delivery_date_time', $this->dob);
    }

    /**
     * Get all visits for this child.
     */
    public function childVisits(): HasMany
    {
        return $this->hasMany(ChildVisit::class);
    }

    /**
     * Get all general visits (poli umum) for this child.
     */
    public function generalVisits(): HasMany
    {
        return $this->hasMany(GeneralVisit::class);
    }

    /**
     * Get all growth records for this child.
     */
    public function growthRecords(): HasMany
    {
        return $this->hasMany(ChildGrowthRecord::class);
    }

    /**
     * Get the latest growth record for this child.
     */
    public function latestGrowthRecord(): HasOne
    {
        return $this->hasOne(ChildGrowthRecord::class)->latestOfMany('record_date');
    }

    /**
     * Get child's age in months.
     */
    public function getAgeInMonthsAttribute(): int
    {
        return $this->dob->diffInMonths(now());
    }

    /**
     * Get child's age in years.
     */
    public function getAgeInYearsAttribute(): int
    {
        return $this->dob->diffInYears(now());
    }

    /**
     * Get formatted age.
     */
    public function getFormattedAgeAttribute(): string
    {
        $years = $this->age_in_years;
        $months = $this->age_in_months % 12;

        if ($years > 0) {
            return "{$years} tahun {$months} bulan";
        }
        return "{$months} bulan";
    }

    /**
     * Calculate detailed age from birth date to a specific date.
     * Format: "X Tahun Y Bulan Z Hari"
     *
     * @param Carbon|null $toDate Target date (default: now)
     * @return string Detailed age format
     */
    public function getDetailedAge(?Carbon $toDate = null): string
    {
        $toDate = $toDate ?? now();

        if (!$this->dob) {
            return 'Tanggal lahir belum diisi';
        }

        $diff = $this->dob->diff($toDate);

        $years = $diff->y;
        $months = $diff->m;
        $days = $diff->d;

        $parts = [];

        if ($years > 0) {
            $parts[] = "{$years} Tahun";
        }

        if ($months > 0 || $years > 0) {
            $parts[] = "{$months} Bulan";
        }

        if ($days > 0 || ($years == 0 && $months == 0)) {
            $parts[] = "{$days} Hari";
        }

        return implode(' ', $parts);
    }

    /**
     * Calculate age at specific visit date.
     * Useful for visit records to show exact age during that visit.
     *
     * @param Carbon $visitDate
     * @return string
     */
    public function getAgeAtVisit(Carbon $visitDate): string
    {
        return $this->getDetailedAge($visitDate);
    }
}
