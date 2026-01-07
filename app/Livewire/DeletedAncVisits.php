<?php

namespace App\Livewire;

use App\Models\AncVisit;
use Livewire\Component;
use Livewire\WithPagination;

class DeletedAncVisits extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 15;
    public $sortField = 'deleted_at';
    public $sortDirection = 'desc';

    protected $queryString = [
        'search' => ['except' => ''],
        'sortField' => ['except' => 'deleted_at'],
        'sortDirection' => ['except' => 'desc'],
    ];

    public function updatingSearch()
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

    public function getVisitsProperty()
    {
        $query = AncVisit::onlyTrashed()
            ->with(['pregnancy.patient'])
            ->whereHas('pregnancy.patient')
            ->orderBy($this->sortField, $this->sortDirection);

        // Search by patient name, visit code, or deletion reason
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('visit_code', 'like', '%' . $this->search . '%')
                    ->orWhere('deleted_reason', 'like', '%' . $this->search . '%')
                    ->orWhereHas('pregnancy.patient', function ($patientQuery) {
                        $patientQuery->where('name', 'like', '%' . $this->search . '%')
                            ->orWhere('nik', 'like', '%' . $this->search . '%');
                    });
            });
        }

        return $query->paginate($this->perPage);
    }

    public function getStatsProperty()
    {
        return [
            'total' => AncVisit::onlyTrashed()->count(),
            'thisMonth' => AncVisit::onlyTrashed()
                ->whereMonth('deleted_at', now()->month)
                ->whereYear('deleted_at', now()->year)
                ->count(),
        ];
    }

    public function restore($visitId)
    {
        $visit = AncVisit::onlyTrashed()->find($visitId);

        if ($visit) {
            $visit->restore();
            $visitCode = $visit->visit_code;

            session()->flash('success', "Kunjungan {$visitCode} berhasil dipulihkan.");
            $this->dispatch('visit-restored');
        }
    }

    public function forceDelete($visitId)
    {
        $visit = AncVisit::onlyTrashed()->find($visitId);

        if ($visit) {
            $visitCode = $visit->visit_code;
            $visit->forceDelete(); // Permanent delete

            session()->flash('success', "Kunjungan {$visitCode} telah dihapus permanen.");
        }
    }

    public function render()
    {
        return view('livewire.deleted-anc-visits', [
            'visits' => $this->visits,
            'stats' => $this->stats,
        ])->layout('layouts.dashboard');
    }
}