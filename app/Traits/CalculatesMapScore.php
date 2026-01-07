<?php

namespace App\Traits;

trait CalculatesMapScore
{
    /**
     * Calculate Mean Arterial Pressure (MAP).
     *
     * Formula: MAP = Diastolic + 1/3(Systolic - Diastolic)
     *
     * @param int $systolic Tekanan Darah Sistolik
     * @param int $diastolic Tekanan Darah Diastolik
     * @return float MAP Score
     */
    public function calculateMAP(int $systolic, int $diastolic): float
    {
        return round($diastolic + (($systolic - $diastolic) / 3), 2);
    }

    /**
     * Get MAP risk level based on score.
     *
     * @param float $mapScore
     * @return string Risk level: BAHAYA, WASPADA, or NORMAL
     */
    public function getMapRiskLevel(float $mapScore): string
    {
        if ($mapScore > 100) {
            return 'BAHAYA';
        }

        if ($mapScore > 90) {
            return 'WASPADA';
        }

        return 'NORMAL';
    }

    /**
     * Get color code for risk level.
     *
     * @param string $riskLevel
     * @return string Tailwind CSS color class
     */
    public function getMapRiskColor(string $riskLevel): string
    {
        return match ($riskLevel) {
            'BAHAYA' => 'text-red-600 bg-red-50',
            'WASPADA' => 'text-yellow-600 bg-yellow-50',
            'NORMAL' => 'text-green-600 bg-green-50',
            default => 'text-gray-600 bg-gray-50',
        };
    }
}
