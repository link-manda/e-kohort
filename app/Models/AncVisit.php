<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AncVisit extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'pregnancy_id',
        'visit_date',
        'trimester',
        'visit_code',
        'gestational_age',
        'weight',
        'height',
        'lila',
        'systolic',
        'diastolic',
        'map_score',
        'tfu',
        'djj',
        'hb',
        'protein_urine',
        'hiv_status',
        'syphilis_status',
        'hbsag_status',
        'tt_immunization',
        'fe_tablets',
        'risk_category',
        'diagnosis',
        'referral_target',
    ];

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'visit_date' => 'date',
            'weight' => 'decimal:2',
            'height' => 'decimal:2',
            'lila' => 'decimal:1',
            'map_score' => 'decimal:2',
            'hb' => 'decimal:1',
        ];
    }

    /**
     * Get the pregnancy that owns this ANC visit.
     */
    public function pregnancy(): BelongsTo
    {
        return $this->belongsTo(Pregnancy::class);
    }

    /**
     * Calculate MAP (Mean Arterial Pressure).
     * Formula: MAP = Diastolic + 1/3(Systolic - Diastolic)
     */
    public function calculateMAP(): float
    {
        if (!$this->systolic || !$this->diastolic) {
            return 0;
        }
        return round($this->diastolic + (($this->systolic - $this->diastolic) / 3), 2);
    }

    /**
     * Get MAP risk level.
     */
    public function getMapRiskLevel(): string
    {
        $map = $this->map_score ?? $this->calculateMAP();

        if ($map > 100) return 'BAHAYA';
        if ($map > 90) return 'WASPADA';
        return 'NORMAL';
    }

    /**
     * Check if patient has KEK (Kurang Energi Kronis).
     */
    public function hasKEK(): bool
    {
        return $this->lila && $this->lila < 23.5;
    }

    /**
     * Check if patient has anemia.
     */
    public function hasAnemia(): bool
    {
        return $this->hb && $this->hb < 11;
    }

    /**
     * Check if any triple elimination test is reactive.
     */
    public function hasTripleEliminationRisk(): bool
    {
        return $this->hiv_status === 'R'
            || $this->syphilis_status === 'R'
            || $this->hbsag_status === 'R';
    }

    /**
     * Detect overall risk category.
     */
    public function detectRiskCategory(): string
    {
        if ($this->hasTripleEliminationRisk()) {
            return 'Ekstrem';
        }

        if (
            $this->getMapRiskLevel() === 'BAHAYA'
            || $this->systolic >= 140
            || $this->hasKEK()
            || $this->hasAnemia()
        ) {
            return 'Tinggi';
        }

        return 'Rendah';
    }
}
