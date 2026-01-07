<?php

namespace App\Livewire;

use App\Models\Pregnancy;
use App\Models\AncVisit;
use Livewire\Component;
use Livewire\WithPagination;

class AncVisitHistory extends Component
{
    use WithPagination;

    public Pregnancy $pregnancy;

    // Filters
    public $riskFilter = 'all'; // all, ekstrem, tinggi, rendah
    public $visitCodeFilter = 'all'; // all, K1, K2, K3, K4, K5, K6
    public $search = '';
    public $perPage = 10;

    // Sorting
    public $sortField = 'visit_date';
    public $sortDirection = 'desc';

    // UI State
    public $expandedVisits = [];

    // Delete Modal State
    public $showDeleteModal = false;
    public $visitToDelete = null;
    public $deleteReason = '';

    protected $queryString = [
        'riskFilter' => ['except' => 'all'],
        'visitCodeFilter' => ['except' => 'all'],
        'search' => ['except' => ''],
        'sortField' => ['except' => 'visit_date'],
        'sortDirection' => ['except' => 'desc'],
    ];

    public function mount(Pregnancy $pregnancy)
    {
        $this->pregnancy = $pregnancy;
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingRiskFilter()
    {
        $this->resetPage();
    }

    public function updatingVisitCodeFilter()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function toggleExpand($visitId)
    {
        if (in_array($visitId, $this->expandedVisits)) {
            $this->expandedVisits = array_diff($this->expandedVisits, [$visitId]);
        } else {
            $this->expandedVisits[] = $visitId;
        }
    }

    public function clearFilters()
    {
        $this->reset(['riskFilter', 'visitCodeFilter', 'search']);
        $this->resetPage();
    }

    public function getVisitsProperty()
    {
        $query = $this->pregnancy->ancVisits()
            ->orderBy($this->sortField, $this->sortDirection);

        // Apply risk filter
        if ($this->riskFilter !== 'all') {
            $query->where('risk_category', ucfirst($this->riskFilter));
        }

        // Apply visit code filter
        if ($this->visitCodeFilter !== 'all') {
            $query->where('visit_code', strtoupper($this->visitCodeFilter));
        }

        // Apply search (by date or notes)
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('clinical_notes', 'like', '%' . $this->search . '%')
                    ->orWhere('anamnesis', 'like', '%' . $this->search . '%');
            });
        }

        return $query->paginate($this->perPage);
    }

    public function getStatsProperty()
    {
        return [
            'total' => $this->pregnancy->ancVisits()->count(),
            'ekstrem' => $this->pregnancy->ancVisits()->where('risk_category', 'Ekstrem')->count(),
            'tinggi' => $this->pregnancy->ancVisits()->where('risk_category', 'Tinggi')->count(),
            'rendah' => $this->pregnancy->ancVisits()->where('risk_category', 'Rendah')->count(),
            'k1' => $this->pregnancy->ancVisits()->where('visit_code', 'K1')->count(),
            'k2' => $this->pregnancy->ancVisits()->where('visit_code', 'K2')->count(),
            'k3' => $this->pregnancy->ancVisits()->where('visit_code', 'K3')->count(),
            'k4' => $this->pregnancy->ancVisits()->where('visit_code', 'K4')->count(),
            'k5' => $this->pregnancy->ancVisits()->where('visit_code', 'K5')->count(),
            'k6' => $this->pregnancy->ancVisits()->where('visit_code', 'K6')->count(),
        ];
    }

    public function deleteVisit($visitId)
    {
        // Open modal with visit info
        $visit = AncVisit::find($visitId);

        if ($visit && $visit->pregnancy_id === $this->pregnancy->id) {
            // Validation: Cannot delete if only visit
            $totalVisits = $this->pregnancy->ancVisits()->count();

            if ($totalVisits <= 1) {
                session()->flash('error', 'Tidak dapat menghapus kunjungan karena ini adalah satu-satunya kunjungan untuk kehamilan ini.');
                return;
            }

            $this->visitToDelete = $visit;
            $this->showDeleteModal = true;
        }
    }

    public function confirmDelete()
    {
        if (!$this->visitToDelete) {
            return;
        }

        // Perform soft delete with reason
        $this->visitToDelete->update([
            'deleted_reason' => $this->deleteReason ?: 'Tidak ada alasan',
            'deleted_by' => auth()->id(), // Will be null if no auth, that's ok
        ]);

        $visitCode = $this->visitToDelete->visit_code;
        $this->visitToDelete->delete(); // Soft delete

        // Reset state
        $this->showDeleteModal = false;
        $this->visitToDelete = null;
        $this->deleteReason = '';

        session()->flash('success', "Kunjungan {$visitCode} berhasil dihapus.");
        $this->dispatch('visit-deleted');
    }

    public function cancelDelete()
    {
        $this->showDeleteModal = false;
        $this->visitToDelete = null;
        $this->deleteReason = '';
    }

    public function exportToExcel()
    {
        // Will be implemented in next iteration
        session()->flash('info', 'Export to Excel coming soon!');
    }

    public function render()
    {
        return view('livewire.anc-visit-history', [
            'visits' => $this->visits,
            'stats' => $this->stats,
        ])->layout('layouts.dashboard');
    }
}