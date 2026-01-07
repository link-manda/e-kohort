<?php

namespace App\Livewire;

use App\Models\Patient;
use Livewire\Component;
use Livewire\WithPagination;

class PatientList extends Component
{
    use WithPagination;

    public $search = '';
    public $pregnancyFilter = 'all'; // all, active, completed, none
    public $riskFilter = 'all'; // all, high, low
    public $perPage = 20;

    protected $queryString = [
        'search' => ['except' => ''],
        'pregnancyFilter' => ['except' => 'all'],
        'riskFilter' => ['except' => 'all'],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingPregnancyFilter()
    {
        $this->resetPage();
    }

    public function updatingRiskFilter()
    {
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->search = '';
        $this->pregnancyFilter = 'all';
        $this->riskFilter = 'all';
        $this->resetPage();
    }

    public function getPatients()
    {
        $query = Patient::with(['pregnancies' => function ($q) {
            $q->where('status', 'Aktif')
                ->with(['ancVisits' => function ($av) {
                    $av->latest()->limit(1);
                }]);
        }]);

        // Search by NIK or Name
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('nik', 'like', '%' . $this->search . '%')
                    ->orWhere('name', 'like', '%' . $this->search . '%');
            });
        }

        // Filter by pregnancy status
        if ($this->pregnancyFilter === 'active') {
            $query->whereHas('pregnancies', function ($q) {
                $q->where('status', 'Aktif');
            });
        } elseif ($this->pregnancyFilter === 'completed') {
            $query->whereHas('pregnancies', function ($q) {
                $q->where('status', 'Lahir');
            });
        } elseif ($this->pregnancyFilter === 'none') {
            $query->doesntHave('pregnancies');
        }

        // Filter by risk level
        if ($this->riskFilter === 'high') {
            $query->whereHas('pregnancies.ancVisits', function ($q) {
                $q->where(function ($av) {
                    $av->where('map_score', '>', 90)
                        ->orWhere('hiv_status', 'R')
                        ->orWhere('syphilis_status', 'R')
                        ->orWhere('hbsag_status', 'R')
                        ->orWhere('lila', '<', 23.5)
                        ->orWhere('hb', '<', 11);
                });
            });
        } elseif ($this->riskFilter === 'low') {
            $query->whereHas('pregnancies.ancVisits', function ($q) {
                $q->where('risk_category', 'Rendah');
            });
        }

        return $query->orderBy('created_at', 'desc')->paginate($this->perPage);
    }

    public function render()
    {
        $patients = $this->getPatients();

        // Calculate statistics for filters
        $totalPatients = Patient::count();
        $activePregnancies = Patient::whereHas('pregnancies', function ($q) {
            $q->where('status', 'Aktif');
        })->count();
        $completedPregnancies = Patient::whereHas('pregnancies', function ($q) {
            $q->where('status', 'Lahir');
        })->count();
        $noPregnancy = Patient::doesntHave('pregnancies')->count();

        return view('livewire.patient-list', [
            'patients' => $patients,
            'stats' => [
                'total' => $totalPatients,
                'active' => $activePregnancies,
                'completed' => $completedPregnancies,
                'none' => $noPregnancy,
            ]
        ])->layout('layouts.dashboard');
    }
}
