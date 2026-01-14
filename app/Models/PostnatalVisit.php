<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PostnatalVisit extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'pregnancy_id',
        'visit_date',
        'visit_code',
        'td_systolic',
        'td_diastolic',
        'temperature',
        'lochea',
        'uterine_involution',
        'vitamin_a',
        'fe_tablets',
        'complication_check',
        'conclusion',
    ];

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'visit_date' => 'date',
            'temperature' => 'decimal:1',
            'vitamin_a' => 'boolean',
            'complication_check' => 'boolean',
        ];
    }

    /**
     * Get the pregnancy that owns this postnatal visit.
     */
    public function pregnancy(): BelongsTo
    {
        return $this->belongsTo(Pregnancy::class);
    }

    /**
     * Get visit code label.
     */
    public function getVisitCodeLabelAttribute(): string
    {
        $labels = [
            'KF1' => 'KF1 (6 jam - 2 hari)',
            'KF2' => 'KF2 (3 - 7 hari)',
            'KF3' => 'KF3 (8 - 28 hari)',
            'KF4' => 'KF4 (29 - 42 hari)',
        ];

        return $labels[$this->visit_code] ?? $this->visit_code;
    }

    /**
     * Validate visit date against visit code schedule.
     */
    public function isVisitDateValid(): bool
    {
        $deliveryDate = $this->pregnancy->delivery_date;
        if (!$deliveryDate) {
            return false;
        }

        $daysSinceDelivery = $deliveryDate->diffInDays($this->visit_date);

        return match ($this->visit_code) {
            'KF1' => $daysSinceDelivery >= 0 && $daysSinceDelivery <= 2,
            'KF2' => $daysSinceDelivery >= 3 && $daysSinceDelivery <= 7,
            'KF3' => $daysSinceDelivery >= 8 && $daysSinceDelivery <= 28,
            'KF4' => $daysSinceDelivery >= 29 && $daysSinceDelivery <= 42,
            default => false,
        };
    }
}
