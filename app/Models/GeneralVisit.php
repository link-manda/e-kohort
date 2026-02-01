<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GeneralVisit extends Model
{
    protected $fillable = [
        'patient_id',
        'visit_date',
        'complaint',
        'systolic',
        'diastolic',
        'temperature',
        'weight',
        'height',
        'physical_exam',
        'diagnosis',
        'icd10_code',
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
        ];
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }
}
