<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GeneralVisit extends Model
{
    protected $fillable = [
        'patient_id',
        'child_id',  // NEW: for child visits
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
        'service_fee',
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
            'service_fee' => 'decimal:2',
        ];
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function child(): BelongsTo
    {
        return $this->belongsTo(Child::class);
    }

    public function prescriptions(): HasMany
    {
        return $this->hasMany(Prescription::class);
    }

    // =============================================
    // Helper Methods for Universal Patient/Child Access
    // =============================================

    /**
     * Check if this visit is for a child
     */
    public function isChildVisit(): bool
    {
        return $this->child_id !== null;
    }

    /**
     * Get the visitor name (Patient or Child)
     */
    public function getVisitorNameAttribute(): string
    {
        if ($this->child_id && $this->child) {
            return $this->child->name;
        }
        if ($this->patient_id && $this->patient) {
            return $this->patient->name;
        }
        return 'Tidak Diketahui';
    }

    /**
     * Get the visitor age
     */
    public function getVisitorAgeAttribute(): ?string
    {
        if ($this->child_id && $this->child) {
            return $this->child->formatted_age ?? null;
        }
        if ($this->patient_id && $this->patient) {
            return $this->patient->age . ' tahun';
        }
        return null;
    }

    /**
     * Get the visitor RM number
     */
    public function getVisitorNoRmAttribute(): ?string
    {
        if ($this->child_id && $this->child) {
            return $this->child->no_rm;
        }
        if ($this->patient_id && $this->patient) {
            return $this->patient->no_rm;
        }
        return null;
    }

    /**
     * Get the actual visitor model (Patient or Child)
     */
    public function getVisitor(): Patient|Child|null
    {
        if ($this->child_id) {
            return $this->child;
        }
        return $this->patient;
    }
    /**
     * Get Total Price (Service Fee + Sum of Prescriptions)
     */
    public function getTotalPriceAttribute(): float
    {
        $serviceFee = $this->service_fee ?? 0;
        $prescriptionTotal = $this->prescriptions->sum('total_price');
        return $serviceFee + $prescriptionTotal;
    }
}
