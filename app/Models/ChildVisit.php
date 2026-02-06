<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ChildVisit extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'child_id',
        'visit_date',
        'age_month',
        'complaint',
        'weight',
        'height',
        'temperature',
        'heart_rate',
        'respiratory_rate',
        'head_circumference',
        'development_notes',
        'icd_code',
        'diagnosis_name',
        // NEW: Additional fields for Template Excel
        'nutritional_status',
        'informed_consent',
        'medicine_given',
        'medicine_dosage',
        'notes',
        // Payment fields
        'service_fee',
        'payment_method',
    ];

    protected function casts(): array
    {
        return [
            'visit_date' => 'datetime',
            'age_month' => 'integer',
            'weight' => 'float',
            'height' => 'float',
            'temperature' => 'float',
            'heart_rate' => 'integer',
            'respiratory_rate' => 'integer',
            'head_circumference' => 'float',
            'informed_consent' => 'boolean',
            'service_fee' => 'decimal:2',
        ];
    }

    /**
     * Get the child that owns this visit.
     */
    public function child(): BelongsTo
    {
        return $this->belongsTo(Child::class);
    }

    /**
     * Get all immunization actions for this visit.
     */
    public function immunizationActions(): HasMany
    {
        return $this->hasMany(ImmunizationAction::class);
    }

    /**
     * Check if child has fever (temperature > 37.5°C).
     */
    public function getHasFeverAttribute(): bool
    {
        return $this->temperature && $this->temperature > 37.5;
    }

    /**
     * Get fever status message.
     */
    public function getFeverStatusAttribute(): string
    {
        if (!$this->temperature) {
            return 'Tidak ada data suhu';
        }

        if ($this->temperature >= 38) {
            return 'Demam Tinggi - Tunda Imunisasi';
        } elseif ($this->temperature > 37.5) {
            return 'Subfebris - Pertimbangkan Tunda';
        }

        return 'Normal';
    }
}
