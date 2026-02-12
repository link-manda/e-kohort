<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\Patient;

class PatientPreview extends Component
{
    public $open = false;
    public $patientId;
    public $patient;

    #[On('openPatientPreview')]
    public function open($id)
    {
        $this->patientId = $id;
        $this->patient = Patient::with([
            'pregnancies',
            'kbVisits' => function($q) {
                $q->with('kbMethod')->latest()->limit(5);
            }
        ])->find($id);

        // Calculate enhanced KB data
        if ($this->patient && $this->patient->kbVisits->count() > 0) {
            $latestVisit = $this->patient->kbVisits->first();

            // 1. KB Active Status
            $isActive = $latestVisit->visit_date->diffInMonths(now()) <= 6;
            $daysSinceStart = $latestVisit->visit_date->diffInDays(now());

            // 2. Next Visit Alert
            $daysUntilNext = null;
            $isOverdue = false;
            $alertLevel = 'normal'; // normal, upcoming, overdue

            if ($latestVisit->next_visit_date) {
                $daysUntilNext = now()->diffInDays($latestVisit->next_visit_date, false);
                $isOverdue = $daysUntilNext < 0;

                if ($isOverdue) {
                    $alertLevel = 'overdue';
                } elseif ($daysUntilNext <= 7) {
                    $alertLevel = 'upcoming';
                }
            }

            // 3. Health Status
            $hasHypertension = ($latestVisit->blood_pressure_systolic >= 140)
                            || ($latestVisit->blood_pressure_diastolic >= 90);
            $hasSideEffects = !empty($latestVisit->side_effects);
            $hasComplications = !empty($latestVisit->complications);

            // 4. Visit Count
            $visitCount = $this->patient->kbVisits()->count();

            // Attach to patient for easy access in view
            $this->patient->kb_status = (object) [
                'is_active' => $isActive,
                'current_method' => $latestVisit->kbMethod->name ?? '-',
                'method_brand' => $latestVisit->contraception_brand,
                'start_date' => $latestVisit->visit_date,
                'days_since_start' => $daysSinceStart,
                'visit_count' => $visitCount,
                'last_visit_date' => $latestVisit->visit_date,
                'next_visit_date' => $latestVisit->next_visit_date,
                'days_until_next' => $daysUntilNext,
                'is_overdue' => $isOverdue,
                'alert_level' => $alertLevel,
                'bp_systolic' => $latestVisit->blood_pressure_systolic,
                'bp_diastolic' => $latestVisit->blood_pressure_diastolic,
                'has_hypertension' => $hasHypertension,
                'weight' => $latestVisit->weight,
                'side_effects' => $latestVisit->side_effects,
                'complications' => $latestVisit->complications,
                'has_health_alerts' => $hasHypertension || $hasSideEffects || $hasComplications,
                'service_fee' => $latestVisit->service_fee,
            ];
        }

        $this->open = true;
    }

    public function close()
    {
        $this->open = false;
    }

    public function render()
    {
        return view('livewire.patient-preview');
    }
}
