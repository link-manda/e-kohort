<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'title',
        'message',
        'link',
        'patient_id',
        'anc_visit_id',
        'is_read',
        'read_at',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
    ];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function ancVisit(): BelongsTo
    {
        return $this->belongsTo(AncVisit::class);
    }

    // Scopes
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeRecent($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    // Methods
    public function markAsRead(): void
    {
        $this->update([
            'is_read' => true,
            'read_at' => now(),
        ]);
    }

    // Static factory methods
    public static function createHighRiskAlert(User $user, Patient $patient, AncVisit $visit, string $riskReason): self
    {
        return self::create([
            'user_id' => $user->id,
            'type' => 'high_risk',
            'title' => '⚠️ Pasien Berisiko Tinggi Terdeteksi',
            'message' => "Pasien {$patient->nama_lengkap} terdeteksi {$riskReason}",
            'link' => route('patients.show', $patient->id),
            'patient_id' => $patient->id,
            'anc_visit_id' => $visit->id,
        ]);
    }

    public static function createTripleEliminasiAlert(User $user, Patient $patient, AncVisit $visit): self
    {
        return self::create([
            'user_id' => $user->id,
            'type' => 'triple_eliminasi',
            'title' => '🔴 Triple Eliminasi Reaktif',
            'message' => "Pasien {$patient->nama_lengkap} terdeteksi hasil Triple Eliminasi Reaktif",
            'link' => route('patients.show', $patient->id),
            'patient_id' => $patient->id,
            'anc_visit_id' => $visit->id,
        ]);
    }
}
