<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChildGrowthRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'child_id',
        'record_date',
        'age_in_months',
        'weight',
        'height',
        'head_circumference',
        'measurement_method',
        'zscore_bb_u',
        'status_bb_u',
        'zscore_tb_u',
        'status_tb_u',
        'zscore_bb_tb',
        'status_bb_tb',
        'vitamin_a',
        'deworming_medicine',
        'pmt_given',
        'notes',
        'midwife_name',
    ];

    protected $casts = [
        'record_date' => 'date',
        'age_in_months' => 'integer',
        'weight' => 'float',
        'height' => 'float',
        'head_circumference' => 'float',
        'zscore_bb_u' => 'float',
        'zscore_tb_u' => 'float',
        'zscore_bb_tb' => 'float',
        'deworming_medicine' => 'boolean',
        'pmt_given' => 'boolean',
    ];

    /**
     * Relasi ke child
     */
    public function child()
    {
        return $this->belongsTo(Child::class);
    }

    /**
     * Scope untuk filter anak yang stunting
     */
    public function scopeStunting($query)
    {
        return $query->where('zscore_tb_u', '<', -2);
    }

    /**
     * Scope untuk filter anak yang wasting
     */
    public function scopeWasting($query)
    {
        return $query->where('zscore_bb_tb', '<', -2);
    }

    /**
     * Scope untuk filter anak yang underweight
     */
    public function scopeUnderweight($query)
    {
        return $query->where('zscore_bb_u', '<', -2);
    }

    /**
     * Check apakah record ini menunjukkan stunting
     */
    public function getIsStuntingAttribute()
    {
        return $this->zscore_tb_u !== null && $this->zscore_tb_u < -2;
    }

    /**
     * Check apakah record ini menunjukkan wasting
     */
    public function getIsWastingAttribute()
    {
        return $this->zscore_bb_tb !== null && $this->zscore_bb_tb < -2;
    }

    /**
     * Check apakah record ini menunjukkan underweight
     */
    public function getIsUnderweightAttribute()
    {
        return $this->zscore_bb_u !== null && $this->zscore_bb_u < -2;
    }
}
