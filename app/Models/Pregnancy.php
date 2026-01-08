<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pregnancy extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'patient_id',
        'gravida',
        'hpht',
        'hpl',
        'pregnancy_gap',
        'weight_before',
        'height',
        'status',
        'risk_score_initial',
    ];

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'hpht' => 'date',
            'hpl' => 'date',
        ];
    }

    /**
     * Get the patient that owns this pregnancy.
     */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    /**
     * Get all ANC visits for this pregnancy.
     */
    public function ancVisits(): HasMany
    {
        return $this->hasMany(AncVisit::class);
    }

    /**
     * Calculate gestational age in weeks from HPHT.
     */
    public function getGestationalAgeAttribute(): int
    {
        if (!$this->hpht) {
            return 0;
        }
        return (int) $this->hpht->diffInWeeks(now());
    }
}
