<?php

namespace App\Traits;

trait DetectsRisk
{
    /**
     * Check if patient has KEK (Kurang Energi Kronis).
     * LILA < 23.5 cm indicates chronic energy deficiency.
     *
     * @param float|null $lila Lingkar Lengan Atas
     * @return bool
     */
    public function hasKEK(?float $lila): bool
    {
        return $lila && $lila < 23.5;
    }

    /**
     * Check if patient has anemia.
     * Hemoglobin < 11 g/dL indicates anemia in pregnant women.
     *
     * @param float|null $hb Hemoglobin level
     * @return bool
     */
    public function hasAnemia(?float $hb): bool
    {
        return $hb && $hb < 11;
    }

    /**
     * Check if patient has hypertension.
     * Systolic >= 140 OR Diastolic >= 90 indicates hypertension.
     *
     * @param int|null $systolic
     * @param int|null $diastolic
     * @return bool
     */
    public function hasHypertension(?int $systolic, ?int $diastolic): bool
    {
        return ($systolic && $systolic >= 140) || ($diastolic && $diastolic >= 90);
    }

    /**
     * Check if any Triple Elimination test is reactive.
     *
     * Triple Elimination: HIV, Syphilis, Hepatitis B
     *
     * @param string|null $hivStatus
     * @param string|null $syphilisStatus
     * @param string|null $hbsagStatus
     * @return bool
     */
    public function hasTripleEliminationRisk(
        ?string $hivStatus,
        ?string $syphilisStatus,
        ?string $hbsagStatus
    ): bool {
        return $hivStatus === 'R'
            || $syphilisStatus === 'R'
            || $hbsagStatus === 'R';
    }

    /**
     * Detect overall risk category based on all indicators.
     *
     * @param array $indicators
     * @return string Rendah, Tinggi, or Ekstrem
     */
    public function detectOverallRisk(array $indicators): string
    {
        // Ekstrem: Triple Elimination Reactive
        if ($indicators['triple_elimination'] ?? false) {
            return 'Ekstrem';
        }

        // Tinggi: MAP > 100, Hypertension, KEK, or Anemia
        $highRiskFactors = [
            $indicators['map_bahaya'] ?? false,
            $indicators['hypertension'] ?? false,
            $indicators['kek'] ?? false,
            $indicators['anemia'] ?? false,
        ];

        if (in_array(true, $highRiskFactors, true)) {
            return 'Tinggi';
        }

        return 'Rendah';
    }

    /**
     * Get risk category color for UI display.
     *
     * @param string $category
     * @return string Tailwind CSS classes
     */
    public function getRiskCategoryColor(string $category): string
    {
        return match ($category) {
            'Ekstrem' => 'bg-red-600 text-white',
            'Tinggi' => 'bg-orange-500 text-white',
            'Rendah' => 'bg-green-500 text-white',
            default => 'bg-gray-500 text-white',
        };
    }
}
