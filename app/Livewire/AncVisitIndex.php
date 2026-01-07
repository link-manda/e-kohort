<?php

namespace App\Livewire;

use App\Models\AncVisit;
use Livewire\Component;
use Livewire\WithPagination;

class AncVisitIndex extends Component
{
    use WithPagination;

    // Filters
    public $riskFilter = 'all';
    public $visitCodeFilter = 'all';
    public $search = '';
    public $perPage = 15;

    // Sorting
    public $sortField = 'visit_date';
    public $sortDirection = 'desc';

    protected $queryString = [
        'riskFilter' => ['except' => 'all'],
        'visitCodeFilter' => ['except' => 'all'],
        'search' => ['except' => ''],
        'sortField' => ['except' => 'visit_date'],
        'sortDirection' => ['except' => 'desc'],
    ];

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function clearFilters()
    {
        $this->reset(['riskFilter', 'visitCodeFilter', 'search']);
    }

    public function getVisitsProperty()
    {
        $query = AncVisit::with(['pregnancy.patient'])
            // Only show visits that have valid pregnancy and patient relationships
            ->whereHas('pregnancy.patient')
            ->orderBy($this->sortField, $this->sortDirection);

        // Filter by risk category
        if ($this->riskFilter !== 'all') {
            $query->where('risk_category', ucfirst($this->riskFilter));
        }

        // Filter by visit code
        if ($this->visitCodeFilter !== 'all') {
            $query->where('visit_code', strtoupper($this->visitCodeFilter));
        }

        // Search in patient name, anamnesis, or clinical notes
        if ($this->search) {
            $query->where(function ($q) {
                $q->whereHas('pregnancy.patient', function ($patientQuery) {
                    $patientQuery->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('nik', 'like', '%' . $this->search . '%');
                })
                    ->orWhere('anamnesis', 'like', '%' . $this->search . '%')
                    ->orWhere('clinical_notes', 'like', '%' . $this->search . '%');
            });
        }

        return $query->paginate($this->perPage);
    }

    public function getStatsProperty()
    {
        $baseQuery = AncVisit::query();

        return [
            'total' => $baseQuery->count(),
            'ekstrem' => (clone $baseQuery)->where('risk_category', 'Ekstrem')->count(),
            'tinggi' => (clone $baseQuery)->where('risk_category', 'Tinggi')->count(),
            'rendah' => (clone $baseQuery)->where('risk_category', 'Rendah')->count(),
            'k1' => (clone $baseQuery)->where('visit_code', 'K1')->count(),
            'k2' => (clone $baseQuery)->where('visit_code', 'K2')->count(),
            'k3' => (clone $baseQuery)->where('visit_code', 'K3')->count(),
            'k4' => (clone $baseQuery)->where('visit_code', 'K4')->count(),
            'k5' => (clone $baseQuery)->where('visit_code', 'K5')->count(),
            'k6' => (clone $baseQuery)->where('visit_code', 'K6')->count(),
        ];
    }

    public function render()
    {
        return view('livewire.anc-visit-index', [
            'visits' => $this->visits,
            'stats' => $this->stats,
        ])->layout('layouts.dashboard');
    }
}
