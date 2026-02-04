<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Prescription extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'general_visit_id',
        'medicine_name',
        'quantity',
        'signa',
        'unit_price',
        'quantity_number',
        'total_price',
        'dosage',
        'frequency',
        'duration',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'unit_price' => 'decimal:2',
            'total_price' => 'decimal:2',
            'quantity_number' => 'integer',
        ];
    }

    /**
     * Relasi ke General Visit
     */
    public function generalVisit(): BelongsTo
    {
        return $this->belongsTo(GeneralVisit::class);
    }

    /**
     * Auto calculate total_price sebelum save
     * total_price = unit_price * quantity_number
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($prescription) {
            if ($prescription->unit_price && $prescription->quantity_number) {
                $prescription->total_price = $prescription->unit_price * $prescription->quantity_number;
            }
        });
    }
}
