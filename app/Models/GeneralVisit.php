<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GeneralVisit extends Model
{
    protected $fillable = [
        'patient_id',
        'visit_date',

        // Subjective (Anamnesa)
        'complaint',
        'allergies',
        'medical_history',
        'consciousness',
        'is_emergency',

        // Objective - Vital Signs
        'systolic',
        'diastolic',
        'temperature',
        'respiratory_rate',
        'heart_rate',
        'weight',
        'height',
        'waist_circumference',
        'bmi',

        // Objective - Physical
        'physical_exam',
        'physical_assessment_details',

        // Lifestyle (Skrining PTM)
        'lifestyle_smoking',
        'lifestyle_alcohol',
        'lifestyle_activity',
        'lifestyle_diet',

        // Assessment
        'diagnosis',
        'icd10_code',

        // Plan
        'therapy',
        'status',
        'payment_method',
    ];

    protected function casts(): array
    {
        return [
            'visit_date' => 'datetime',
            'temperature' => 'decimal:1',
            'weight' => 'decimal:2',
            'height' => 'decimal:2',
            'waist_circumference' => 'decimal:2',
            'bmi' => 'decimal:2',
            'is_emergency' => 'boolean',
            'lifestyle_alcohol' => 'boolean',
            'physical_assessment_details' => 'array', // JSON cast
        ];
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function prescriptions(): HasMany
    {
        return $this->hasMany(Prescription::class);
    }
}
