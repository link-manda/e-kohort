<?php

namespace App\Livewire;

use App\Models\Child;
use Livewire\Component;
use Livewire\WithPagination;

class GrowthIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = 'all'; // all, stunting, wasting, normal
    public $perPage = 20;

    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => 'all'],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function getChildren()
    {
        $query = Child::with(['patient', 'latestGrowthRecord']);

        // Search
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('no_rm', 'like', '%' . $this->search . '%')
                    ->orWhereHas('patient', function ($q2) {
                        $q2->where('name', 'like', '%' . $this->search . '%')
                            ->orWhere('nik', 'like', '%' . $this->search . '%');
                    });
            });
        }

        // Filter by Status Gizi (based on latest record)
        if ($this->statusFilter !== 'all') {
            $query->whereHas('latestGrowthRecord', function ($q) {
                if ($this->statusFilter === 'stunting') {
                    // Stunting: TB/U < -2 SD
                    $q->where('zscore_tb_u', '<', -2);
                } elseif ($this->statusFilter === 'wasting') {
                    // Wasting: BB/TB < -2 SD
                    $q->where('zscore_bb_tb', '<', -2);
                } elseif ($this->statusFilter === 'underweight') {
                     // Underweight: BB/U < -2 SD
                    $q->where('zscore_bb_u', '<', -2);
                } elseif ($this->statusFilter === 'normal') {
                    // Normal: All >= -2 SD AND <= +2 SD (Simplified)
                    $q->where('zscore_bb_u', '>=', -2)
                      ->where('zscore_tb_u', '>=', -2)
                      ->where('zscore_bb_tb', '>=', -2);
                }
            });
        }

        return $query->orderBy('name', 'asc')->paginate($this->perPage);
    }

    public function render()
    {
        $children = $this->getChildren();

        return view('livewire.growth-index', [
            'children' => $children,
        ])->layout('layouts.dashboard');
    }
}
