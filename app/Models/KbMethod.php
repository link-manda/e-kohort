<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KbMethod extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code',
        'name',
        'category',
        'is_hormonal',
        'is_active',
    ];

    protected $casts = [
        'is_hormonal' => 'boolean',
        'is_active' => 'boolean',
    ];

    /**
     * Get all visits using this method
     */
    public function visits()
    {
        return $this->hasMany(KbVisit::class);
    }

    /**
     * Scope for active methods only
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for non-hormonal methods (safe for hypertension)
     */
    public function scopeNonHormonal($query)
    {
        return $query->where('is_hormonal', false);
    }
}
