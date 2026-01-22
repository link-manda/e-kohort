<?php

namespace App\Services;

use App\Models\KbMethod;
use App\Models\KbVisit;
use Carbon\Carbon;

class KbSchedulingService
{
    /**
     * Calculate the next visit date based on KB method and visit history
     *
     * @param KbVisit $visit
     * @return Carbon
     */
    public function calculateNextVisitDate(KbVisit $visit): Carbon
    {
        $visitDate = Carbon::parse($visit->visit_date);
        $method = $visit->kbMethod;

        switch ($method->category) {
            case 'SUNTIK':
                return $this->calculateInjectableSchedule($method, $visitDate);

            case 'PIL':
                // Pil: +1 month (when pills run out)
                return $visitDate->copy()->addMonth();

            case 'IMPLANT':
            case 'IUD':
                return $this->calculateLongActingSchedule($visit, $visitDate);

            case 'LAINNYA':
                // For Kondom, MOW, MOP: default to 1 month follow-up
                return $visitDate->copy()->addMonth();

            default:
                return $visitDate->copy()->addMonth();
        }
    }

    /**
     * Calculate schedule for injectable contraceptives
     *
     * @param KbMethod $method
     * @param Carbon $visitDate
     * @return Carbon
     */
    private function calculateInjectableSchedule(KbMethod $method, Carbon $visitDate): Carbon
    {
        // Logic Suntik 1 Bulan: visit_date + 28 Days
        if (str_contains($method->name, '1 Bulan')) {
            return $visitDate->copy()->addDays(28);
        }

        // Logic Suntik 3 Bulan: visit_date + 12 Weeks (84 Days)
        if (str_contains($method->name, '3 Bulan')) {
            return $visitDate->copy()->addDays(84);
        }

        // Default fallback
        return $visitDate->copy()->addMonth();
    }

    /**
     * Calculate schedule for long-acting methods (IUD/Implant)
     *
     * @param KbVisit $visit
     * @param Carbon $visitDate
     * @return Carbon
     */
    private function calculateLongActingSchedule(KbVisit $visit, Carbon $visitDate): Carbon
    {
        $isNewInstallation = $this->isNewInstallation($visit);

        if ($isNewInstallation) {
            // Logic IUD/Implant (Pasang Baru): visit_date + 1 Week (Post-installation control)
            return $visitDate->copy()->addWeek();
        } else {
            // Logic IUD/Implant (Kontrol Rutin): visit_date + 1 Year
            return $visitDate->copy()->addYear();
        }
    }

    /**
     * Determine if this is a new installation
     * Check if patient has previous visits with the same method
     *
     * @param KbVisit $visit
     * @return bool
     */
    private function isNewInstallation(KbVisit $visit): bool
    {
        // Check visit_type first
        if ($visit->visit_type === 'Peserta Baru') {
            return true;
        }

        // For "Ganti Cara" (method change), also treat as new installation
        if ($visit->visit_type === 'Ganti Cara') {
            return true;
        }

        // For "Peserta Lama", check if they had previous visits with this method
        $previousVisits = KbVisit::where('patient_id', $visit->patient_id)
            ->where('kb_method_id', $visit->kb_method_id)
            ->where('id', '!=', $visit->id)
            ->where('visit_date', '<', $visit->visit_date)
            ->exists();

        return !$previousVisits;
    }

    /**
     * Validate if a KB method is safe for a patient with hypertension
     *
     * @param KbMethod $method
     * @param int $systolic
     * @param int $diastolic
     * @return bool
     */
    public function isSafeForHypertension(KbMethod $method, int $systolic, int $diastolic): bool
    {
        $isHypertensive = ($systolic >= 140 || $diastolic >= 90);

        if (!$isHypertensive) {
            return true; // No hypertension, all methods are safe
        }

        // If hypertensive, only allow non-hormonal methods
        return !$method->is_hormonal;
    }
}
