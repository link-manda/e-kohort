<?php

namespace App\Services;

use App\Models\Child;
use App\Models\WhoStandard;
use Carbon\Carbon;

class GrowthCalculatorService
{
    /**
     * Hitung umur anak dalam bulan
     */
    public function calculateAgeInMonths(string $dateOfBirth, string $recordDate): int
    {
        $dob = Carbon::parse($dateOfBirth);
        $record = Carbon::parse($recordDate);

        return $dob->diffInMonths($record);
    }

    /**
     * Koreksi tinggi badan berdasarkan metode pengukuran dan umur
     */
    public function correctHeight(float $height, int $ageInMonths, string $measurementMethod): float
    {
        $correctedHeight = $height;

        // Jika diukur Terlentang tapi umur >= 24 bulan (2 tahun): Kurangi 0.7 cm
        if ($measurementMethod === 'Terlentang' && $ageInMonths >= 24) {
            $correctedHeight = $height - 0.7;
        }

        // Jika diukur Berdiri tapi umur < 24 bulan: Tambah 0.7 cm
        if ($measurementMethod === 'Berdiri' && $ageInMonths < 24) {
            $correctedHeight = $height + 0.7;
        }

        return round($correctedHeight, 1);
    }

    /**
     * Hitung Z-Score untuk BB/U (Berat Badan per Umur)
     */
    public function calculateZScoreBBU(string $gender, int $ageInMonths, float $weight): array
    {
        $standard = WhoStandard::where('gender', $gender)
            ->where('type', 'BB_U')
            ->where('age_month', $ageInMonths)
            ->first();

        if (!$standard) {
            return [
                'zscore' => null,
                'status' => null,
                'message' => 'Standar WHO untuk umur ini belum tersedia'
            ];
        }

        $zscore = $this->calculateZScore($weight, $standard);
        $status = $this->determineStatusBBU($zscore);

        return [
            'zscore' => $zscore,
            'status' => $status,
            'reference' => $standard
        ];
    }

    /**
     * Hitung Z-Score untuk TB/U (Tinggi Badan per Umur)
     */
    public function calculateZScoreTBU(string $gender, int $ageInMonths, float $height): array
    {
        $standard = WhoStandard::where('gender', $gender)
            ->where('type', 'TB_U')
            ->where('age_month', $ageInMonths)
            ->first();

        if (!$standard) {
            return [
                'zscore' => null,
                'status' => null,
                'message' => 'Standar WHO untuk umur ini belum tersedia'
            ];
        }

        $zscore = $this->calculateZScore($height, $standard);
        $status = $this->determineStatusTBU($zscore);

        return [
            'zscore' => $zscore,
            'status' => $status,
            'reference' => $standard
        ];
    }

    /**
     * Hitung Z-Score untuk BB/TB (Berat Badan per Tinggi Badan)
     */
    public function calculateZScoreBBTB(string $gender, float $height, float $weight): array
    {
        $standards = WhoStandard::where('gender', $gender)
            ->where('type', 'BB_TB')
            ->orderByRaw('ABS(length_cm - ?)', [$height])
            ->limit(2)
            ->get();

        if ($standards->isEmpty()) {
            return [
                'zscore' => null,
                'status' => null,
                'message' => 'Standar WHO untuk tinggi badan ini belum tersedia'
            ];
        }

        if ($standards->count() === 1 || $standards->first()->length_cm == $height) {
            $standard = $standards->first();
            $zscore = $this->calculateZScore($weight, $standard);
        } else {
            $zscore = $this->interpolateZScore($height, $weight, $standards);
        }

        $status = $this->determineStatusBBTB($zscore);

        return [
            'zscore' => $zscore,
            'status' => $status,
            'reference' => $standards->first()
        ];
    }

    /**
     * Hitung Z-Score berdasarkan rumus WHO
     */
    private function calculateZScore(float $value, WhoStandard $standard): float
    {
        $median = $standard->sd_median;

        if ($value >= $median) {
            if ($value <= $standard->sd_plus_1) {
                $sd = $standard->sd_plus_1 - $median;
            } elseif ($value <= $standard->sd_plus_2) {
                $sd = $standard->sd_plus_2 - $standard->sd_plus_1;
                $median = $standard->sd_plus_1;
            } else {
                $sd = $standard->sd_plus_3 - $standard->sd_plus_2;
                $median = $standard->sd_plus_2;
            }
        } else {
            if ($value >= $standard->sd_minus_1) {
                $sd = $median - $standard->sd_minus_1;
            } elseif ($value >= $standard->sd_minus_2) {
                $sd = $standard->sd_minus_1 - $standard->sd_minus_2;
                $median = $standard->sd_minus_1;
            } else {
                $sd = $standard->sd_minus_2 - $standard->sd_minus_3;
                $median = $standard->sd_minus_2;
            }
        }

        if ($sd == 0) {
            return 0;
        }

        $zscore = ($value - $median) / $sd;

        return round($zscore, 2);
    }

    /**
     * Interpolasi linear untuk Z-Score BB/TB
     */
    private function interpolateZScore(float $height, float $weight, $standards): float
    {
        $lower = $standards->first();
        $upper = $standards->last();

        if ($lower->length_cm > $upper->length_cm) {
            [$lower, $upper] = [$upper, $lower];
        }

        $zscoreLower = $this->calculateZScore($weight, $lower);
        $zscoreUpper = $this->calculateZScore($weight, $upper);

        $heightDiff = $upper->length_cm - $lower->length_cm;
        if ($heightDiff == 0) {
            return $zscoreLower;
        }

        $ratio = ($height - $lower->length_cm) / $heightDiff;
        $interpolatedZScore = $zscoreLower + ($zscoreUpper - $zscoreLower) * $ratio;

        return round($interpolatedZScore, 2);
    }

    /**
     * Tentukan status gizi BB/U
     */
    private function determineStatusBBU(float $zscore): string
    {
        if ($zscore < -3) {
            return 'Gizi Buruk';
        } elseif ($zscore < -2) {
            return 'Kurang';
        } elseif ($zscore <= 1) {
            return 'Baik';
        } else {
            return 'Lebih';
        }
    }

    /**
     * Tentukan status TB/U (Stunting)
     */
    private function determineStatusTBU(float $zscore): string
    {
        if ($zscore < -3) {
            return 'Sangat Pendek';
        } elseif ($zscore < -2) {
            return 'Pendek';
        } elseif ($zscore <= 3) {
            return 'Normal';
        } else {
            return 'Tinggi';
        }
    }

    /**
     * Tentukan status BB/TB (Wasting)
     */
    private function determineStatusBBTB(float $zscore): string
    {
        if ($zscore < -3) {
            return 'Gizi Buruk';
        } elseif ($zscore < -2) {
            return 'Gizi Kurang';
        } elseif ($zscore <= 1) {
            return 'Baik';
        } elseif ($zscore <= 2) {
            return 'Gizi Lebih';
        } else {
            return 'Obesitas';
        }
    }

    /**
     * Hitung semua indikator pertumbuhan sekaligus
     */
    public function calculateAllIndicators(
        Child $child,
        string $recordDate,
        float $weight,
        float $height,
        string $measurementMethod
    ): array {
        $ageInMonths = $this->calculateAgeInMonths($child->dob->format('Y-m-d'), $recordDate);
        $correctedHeight = $this->correctHeight($height, $ageInMonths, $measurementMethod);

        $bbu = $this->calculateZScoreBBU($child->gender, $ageInMonths, $weight);
        $tbu = $this->calculateZScoreTBU($child->gender, $ageInMonths, $correctedHeight);
        $bbtb = $this->calculateZScoreBBTB($child->gender, $correctedHeight, $weight);

        return [
            'age_in_months' => $ageInMonths,
            'corrected_height' => $correctedHeight,
            'height_correction_applied' => $correctedHeight != $height,
            'bb_u' => $bbu,
            'tb_u' => $tbu,
            'bb_tb' => $bbtb,
            'has_stunting' => isset($tbu['zscore']) && $tbu['zscore'] < -2,
            'has_wasting' => isset($bbtb['zscore']) && $bbtb['zscore'] < -2,
            'has_underweight' => isset($bbu['zscore']) && $bbu['zscore'] < -2,
        ];
    }
}
