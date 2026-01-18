<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Vaccine;

class ImmunizationAction extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'child_visit_id',
        'vaccine_type',
        'batch_number',
        'body_part',
        'provider_name',
    ];

    /**
     * List of available vaccines with minimum age requirements (in months).
     */
    public static function getVaccineTypes(): array
    {
        return [
            'HB0' => ['name' => 'Hepatitis B 0', 'min_age' => 0, 'max_age' => 0.23], // < 7 hari
            'BCG' => ['name' => 'BCG', 'min_age' => 0, 'max_age' => 1],
            'Polio 1' => ['name' => 'Polio 1', 'min_age' => 0, 'max_age' => 1],
            'DPT-HB-Hib 1' => ['name' => 'DPT-HB-Hib 1', 'min_age' => 2, 'max_age' => 4],
            'Polio 2' => ['name' => 'Polio 2', 'min_age' => 2, 'max_age' => 4],
            'DPT-HB-Hib 2' => ['name' => 'DPT-HB-Hib 2', 'min_age' => 3, 'max_age' => 5],
            'Polio 3' => ['name' => 'Polio 3', 'min_age' => 3, 'max_age' => 5],
            'DPT-HB-Hib 3' => ['name' => 'DPT-HB-Hib 3', 'min_age' => 4, 'max_age' => 6],
            'Polio 4' => ['name' => 'Polio 4', 'min_age' => 4, 'max_age' => 6],
            'IPV' => ['name' => 'IPV (Inactivated Polio Vaccine)', 'min_age' => 4, 'max_age' => 6],
            'MR' => ['name' => 'MR (Measles Rubella)', 'min_age' => 9, 'max_age' => 12],
        ];
    }

    /**
     * Get the child visit that owns this immunization action.
     */
    public function childVisit(): BelongsTo
    {
        return $this->belongsTo(ChildVisit::class);
    }

    /**
     * Relation: Vaccine master lookup by code
     */
    public function vaccine(): BelongsTo
    {
        // vaccine_type stores vaccine.code value
        return $this->belongsTo(Vaccine::class, 'vaccine_type', 'code');
    }

    /**
     * Get vaccine details.
     */
    public function getVaccineDetailsAttribute(): ?array
    {
        $vaccines = self::getVaccineTypes();
        return $vaccines[$this->vaccine_type] ?? null;
    }

    /**
     * Check if vaccine is appropriate for child's age.
     */
    public static function isAgeAppropriate(string $vaccineType, int $ageInMonths): array
    {
        $vaccines = self::getVaccineTypes();
        $vaccine = $vaccines[$vaccineType] ?? null;

        if (!$vaccine) {
            return ['status' => 'unknown', 'message' => 'Jenis vaksin tidak dikenal'];
        }

        if ($ageInMonths < $vaccine['min_age']) {
            return [
                'status' => 'too_young',
                'message' => "⚠️ Anak terlalu muda untuk vaksin {$vaccine['name']} (minimal {$vaccine['min_age']} bulan)"
            ];
        }

        if ($ageInMonths > $vaccine['max_age']) {
            return [
                'status' => 'late',
                'message' => "⚠️ Vaksin {$vaccine['name']} terlambat (seharusnya {$vaccine['min_age']}-{$vaccine['max_age']} bulan)"
            ];
        }

        return [
            'status' => 'appropriate',
            'message' => "✓ Usia sesuai untuk vaksin {$vaccine['name']}"
        ];
    }
}
