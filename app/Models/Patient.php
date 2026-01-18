<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Patient extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'nik',
        'no_rm',
        'no_kk',
        'no_bpjs',
        'name',
        'dob',
        'pob',
        'address',
        'phone',
        'job',
        'education',
        'blood_type',
        'husband_name',
        'husband_nik',
        'husband_job',
        'husband_education',
        'husband_blood_type',
    ];

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'dob' => 'date',
        ];
    }

    /**
     * Get all pregnancies for this patient.
     */
    public function pregnancies(): HasMany
    {
        return $this->hasMany(Pregnancy::class);
    }

    /**
     * Get the active pregnancy for this patient (includes both active and delivered pregnancies).
     */
    public function activePregnancy()
    {
        return $this->hasOne(Pregnancy::class)->whereIn('status', ['Aktif', 'Lahir']);
    }

    /**
     * Get all children for this patient (mother).
     */
    public function children(): HasMany
    {
        return $this->hasMany(Child::class);
    }

    /**
     * Get patient's age.
     */
    public function getAgeAttribute(): int
    {
        return $this->dob->age;
    }

    /**
     * Check if patient has delivered (has pregnancy with status 'Lahir').
     */
    public function getHasDeliveredAttribute(): bool
    {
        return $this->pregnancies()->where('status', 'Lahir')->exists();
    }
}
