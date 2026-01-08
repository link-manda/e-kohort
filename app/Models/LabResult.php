<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LabResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'anc_visit_id',
        'hb',
        'protein_urine',
        'hiv_status',
        'syphilis_status',
        'hbsag_status',
        'anemia_status',
    ];

    protected $casts = [
        'hb' => 'decimal:2',
    ];

    /**
     * Get the ANC visit that owns the lab result.
     */
    public function ancVisit(): BelongsTo
    {
        return $this->belongsTo(AncVisit::class, 'anc_visit_id');
    }

    /**
     * Calculate anemia status based on Hb level.
     */
    public function calculateAnemiaStatus(): string
    {
        if (!$this->hb) {
            return 'Tidak Diketahui';
        }

        if ($this->hb < 8) {
            return 'Anemia Berat';
        } elseif ($this->hb < 11) {
            return 'Anemia';
        } else {
            return 'Normal';
        }
    }

    /**
     * Boot method to auto-calculate anemia status.
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($labResult) {
            if ($labResult->hb) {
                $labResult->anemia_status = $labResult->calculateAnemiaStatus();
            }
        });
    }
}
