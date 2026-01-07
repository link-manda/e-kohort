<?php

namespace App\Livewire;

use App\Models\Patient;
use App\Models\Pregnancy;
use App\Models\AncVisit;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class Dashboard extends Component
{
    public $totalPatients = 0;
    public $activePregnancies = 0;
    public $todayVisits = 0;
    public $highRiskPatients = 0;

    public $highRiskList = [];
    public $recentVisits = [];

    public function mount()
    {
        $this->loadStatistics();
        $this->loadHighRiskPatients();
        $this->loadRecentVisits();
    }

    public function loadStatistics()
    {
        // Total patients registered
        $this->totalPatients = Patient::count();

        // Active pregnancies
        $this->activePregnancies = Pregnancy::where('status', 'Aktif')->count();

        // Today's ANC visits
        $this->todayVisits = AncVisit::whereDate('visit_date', today())->count();

        // High-risk patients (MAP > 90 OR Triple Eliminasi Reaktif OR KEK OR Anemia)
        $this->highRiskPatients = AncVisit::where(function ($query) {
            $query->where('map_score', '>', 90)
                ->orWhere('hiv_status', 'R')
                ->orWhere('syphilis_status', 'R')
                ->orWhere('hbsag_status', 'R')
                ->orWhere('lila', '<', 23.5)
                ->orWhere('hb', '<', 11);
        })
            ->whereHas('pregnancy', function ($query) {
                $query->where('status', 'Aktif');
            })
            ->distinct('pregnancy_id')
            ->count('pregnancy_id');
    }

    public function loadHighRiskPatients()
    {
        // Get latest visit for each active pregnancy with risk factors
        $this->highRiskList = AncVisit::with(['pregnancy.patient'])
            ->whereHas('pregnancy', function ($query) {
                $query->where('status', 'Aktif');
            })
            ->where(function ($query) {
                $query->where('map_score', '>', 90)
                    ->orWhere('hiv_status', 'R')
                    ->orWhere('syphilis_status', 'R')
                    ->orWhere('hbsag_status', 'R')
                    ->orWhere('lila', '<', 23.5)
                    ->orWhere('hb', '<', 11);
            })
            ->whereIn('id', function ($query) {
                $query->select(DB::raw('MAX(id)'))
                    ->from('anc_visits')
                    ->groupBy('pregnancy_id');
            })
            ->orderBy('risk_category', 'desc')
            ->orderBy('visit_date', 'desc')
            ->limit(10)
            ->get()
            ->filter(function ($visit) {
                return $visit->pregnancy && $visit->pregnancy->patient;
            })
            ->map(function ($visit) {
                $risks = [];

                if ($visit->map_score > 100) {
                    $risks[] = ['type' => 'MAP BAHAYA', 'value' => number_format($visit->map_score, 1), 'color' => 'red'];
                } elseif ($visit->map_score > 90) {
                    $risks[] = ['type' => 'MAP WASPADA', 'value' => number_format($visit->map_score, 1), 'color' => 'yellow'];
                }

                if ($visit->lila && $visit->lila < 23.5) {
                    $risks[] = ['type' => 'KEK', 'value' => $visit->lila . ' cm', 'color' => 'orange'];
                }

                if ($visit->hb && $visit->hb < 11) {
                    $risks[] = ['type' => 'Anemia', 'value' => $visit->hb . ' g/dL', 'color' => 'orange'];
                }

                if ($visit->hiv_status === 'R') {
                    $risks[] = ['type' => 'HIV Reaktif', 'value' => 'R', 'color' => 'red'];
                }

                if ($visit->syphilis_status === 'R') {
                    $risks[] = ['type' => 'Syphilis Reaktif', 'value' => 'R', 'color' => 'red'];
                }

                if ($visit->hbsag_status === 'R') {
                    $risks[] = ['type' => 'HBsAg Reaktif', 'value' => 'R', 'color' => 'red'];
                }

                return [
                    'patient_id' => $visit->pregnancy->patient_id,
                    'patient_name' => $visit->pregnancy->patient->name,
                    'nik' => $visit->pregnancy->patient->nik,
                    'gestational_age' => $visit->gestational_age,
                    'visit_date' => $visit->visit_date,
                    'risk_category' => $visit->risk_category,
                    'risks' => $risks,
                ];
            });
    }

    public function loadRecentVisits()
    {
        $this->recentVisits = AncVisit::with(['pregnancy.patient'])
            ->orderBy('visit_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get()
            ->filter(function ($visit) {
                return $visit->pregnancy && $visit->pregnancy->patient;
            })
            ->map(function ($visit) {
                return [
                    'id' => $visit->id,
                    'patient_id' => $visit->pregnancy->patient_id,
                    'patient_name' => $visit->pregnancy->patient->name,
                    'visit_date' => $visit->visit_date,
                    'visit_code' => $visit->visit_code,
                    'gestational_age' => $visit->gestational_age,
                    'map_score' => $visit->map_score,
                    'risk_category' => $visit->risk_category,
                ];
            });
    }

    public function getRiskColor($category)
    {
        return match ($category) {
            'Ekstrem' => 'red',
            'Tinggi' => 'orange',
            'Rendah' => 'green',
            default => 'gray'
        };
    }

    public function render()
    {
        return view('livewire.dashboard')->layout('layouts.dashboard');
    }
}