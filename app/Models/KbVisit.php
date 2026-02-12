<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KbVisit extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'patient_id',
        'visit_date',
        'no_rm',
        'visit_type',
        'payment_type',
        'weight',
        'blood_pressure_systolic',
        'blood_pressure_diastolic',
        'physical_exam_notes',
        'kb_method_id',
        'contraception_brand',
        'icd_code',
        'diagnosis',
        'side_effects',
        'complications',
        'informed_consent',
        'next_visit_date',
        'midwife_name',
        'service_fee',
    ];

    protected $casts = [
        'visit_date' => 'datetime',
        'next_visit_date' => 'date',
        'weight' => 'float',
        'blood_pressure_systolic' => 'integer',
        'blood_pressure_diastolic' => 'integer',
        'informed_consent' => 'boolean',
    ];

    /**
     * Boot method - auto-populate no_rm from patient
     */
    protected static function booted()
    {
        static::creating(function ($visit) {
            if (!$visit->no_rm && $visit->patient) {
                $visit->no_rm = $visit->patient->no_rm;
            }
        });
    }

    /**
     * Get the patient for this visit
     */
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    /**
     * Get the KB method used
     */
    public function kbMethod()
    {
        return $this->belongsTo(KbMethod::class);
    }

    /**
     * Check if blood pressure indicates hypertension
     */
    public function isHypertensive(): bool
    {
        return $this->blood_pressure_systolic >= 140 || $this->blood_pressure_diastolic >= 90;
    }

    /**
     * Scope for new participants this month
     */
    public function scopeNewParticipants($query)
    {
        return $query->where('visit_type', 'Peserta Baru')
                     ->whereMonth('visit_date', now()->month)
                     ->whereYear('visit_date', now()->year);
    }

    /**
     * Scope for returning participants this month
     */
    public function scopeReturningParticipants($query)
    {
        return $query->where('visit_type', 'Peserta Lama')
                     ->whereMonth('visit_date', now()->month)
                     ->whereYear('visit_date', now()->year);
    }
}
