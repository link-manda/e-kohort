<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DeliveryRecord extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'pregnancy_id',
        'delivery_date_time',
        'gestational_age',
        'birth_attendant',
        'place_of_birth',

        // Data Ibu - Kala I & II
        'duration_first_stage',
        'duration_second_stage',
        'delivery_method',

        // Data Ibu - Kala III & IV
        'placenta_delivery',
        'oxytocin_injection',
        'controlled_cord_traction',
        'uterine_massage',
        'perineum_rupture',
        'bleeding_amount',
        'blood_pressure',
        'postpartum_monitoring_2h',
        'complications',

        // Data Bayi - Identitas & Antropometri
        'baby_name',
        'notes',
        'service_fee',
        'gender',
        'birth_weight',
        'birth_length',
        'head_circumference',

        // Data Bayi - Kondisi Lahir
        'apgar_score_1',
        'apgar_score_5',
        'condition',
        'congenital_defect',

        // Manajemen Bayi Baru Lahir
        'imd_initiated',
        'vit_k_given',
        'eye_ointment_given',
        'hb0_given',
    ];

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'delivery_date_time' => 'datetime',
            'birth_weight' => 'float',
            'birth_length' => 'float',
            'head_circumference' => 'float',

            // Manajemen Aktif Kala 3
            'oxytocin_injection' => 'boolean',
            'controlled_cord_traction' => 'boolean',
            'uterine_massage' => 'boolean',

            // Manajemen Bayi Baru Lahir
            'imd_initiated' => 'boolean',
            'vit_k_given' => 'boolean',
            'eye_ointment_given' => 'boolean',
            'hb0_given' => 'boolean',
        ];
    }

    /**
     * Get the pregnancy that owns this delivery record.
     */
    public function pregnancy(): BelongsTo
    {
        return $this->belongsTo(Pregnancy::class);
    }

    /**
     * Check if delivery has high bleeding risk (> 500ml).
     */
    public function hasHighBleedingRisk(): bool
    {
        return $this->bleeding_amount && $this->bleeding_amount > 500;
    }

    /**
     * Check if baby needs special attention based on APGAR score.
     */
    public function needsNeonatalAttention(): bool
    {
        return ($this->apgar_score_1 && $this->apgar_score_1 < 7)
            || ($this->apgar_score_5 && $this->apgar_score_5 < 7);
    }

    /**
     * Check if all essential newborn management was completed.
     */
    public function hasCompleteNewbornManagement(): bool
    {
        return $this->imd_initiated
            && $this->vit_k_given
            && $this->eye_ointment_given
            && $this->hb0_given;
    }
}

