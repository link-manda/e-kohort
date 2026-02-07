<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

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
        'delivery_date',
        'delivery_method',
        'birth_attendant',
        'place_of_birth',
        'outcome',
        'baby_gender',
        'complications',
        'is_external', // Add for external birth tracking
    ];

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'hpht' => 'date',
            'hpl' => 'date',
            'delivery_date' => 'datetime',
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
     * Get all postnatal visits for this pregnancy.
     */
    public function postnatalVisits(): HasMany
    {
        return $this->hasMany(PostnatalVisit::class);
    }

    /**
     * Get the delivery record for this pregnancy (One-to-One).
     */
    public function deliveryRecord(): HasOne
    {
        return $this->hasOne(DeliveryRecord::class);
    }

    /**
     * Check if delivery data has been recorded.
     */
    public function hasDeliveryRecord(): bool
    {
        return $this->deliveryRecord()->exists();
    }

    /**
     * Get delivery status badge HTML/text for UI display.
     */
    public function getDeliveryStatusBadge(): string
    {
        if ($this->hasDeliveryRecord()) {
            return '<span class="badge badge-success">✅ Sudah Melahirkan</span>';
        }

        if ($this->isReadyForDelivery()) {
            return '<span class="badge badge-warning">⏳ Siap Melahirkan</span>';
        }

        return '<span class="badge badge-secondary">🤰 Hamil</span>';
    }

    /**
     * Check if pregnancy is ready for delivery entry.
     * Criteria: gestational_age >= 37 weeks (full term) OR status = 'Lahir'
     */
    public function isReadyForDelivery(): bool
    {
        return $this->gestational_age >= 37 || $this->status === 'Lahir';
    }

    /**
     * Get delivery summary for quick reference.
     * Returns array with key delivery data from DeliveryRecord.
     */
    public function getDeliverySummaryAttribute(): ?array
    {
        $delivery = $this->deliveryRecord;

        if (!$delivery) {
            return null;
        }

        return [
            'date' => $delivery->delivery_date_time,
            'method' => $delivery->delivery_method,
            'attendant' => $delivery->birth_attendant,
            'place' => $delivery->place_of_birth,
            'baby_name' => $delivery->baby_name,
            'gender' => $delivery->gender,
            'weight' => $delivery->birth_weight,
            'length' => $delivery->birth_length,
            'condition' => $delivery->condition,
        ];
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