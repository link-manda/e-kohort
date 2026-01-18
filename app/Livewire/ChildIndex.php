<?php

namespace App\Livewire;

use App\Models\Child;
use Livewire\Component;
use Livewire\WithPagination;

class ChildIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $immunizationFilter = 'all'; // all, complete, partial, none
    public $ageFilter = 'all'; // all, under1, 1-2, over2
    public $perPage = 20;

    protected $queryString = [
        'search' => ['except' => ''],
        'immunizationFilter' => ['except' => 'all'],
        'ageFilter' => ['except' => 'all'],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingImmunizationFilter()
    {
        $this->resetPage();
    }

    public function updatingAgeFilter()
    {
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->search = '';
        $this->immunizationFilter = 'all';
        $this->ageFilter = 'all';
        $this->resetPage();
    }

    public function getChildren()
    {
        $query = Child::with(['patient', 'childVisits.immunizationActions']);

        // Search by name, no_rm, or mother's name/nik
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

        // Filter by immunization status - simplified for now
        // TODO: Implement proper immunization filtering with subqueries
        // if ($this->immunizationFilter === 'complete') {
        //     // Complex query needed
        // } elseif ($this->immunizationFilter === 'partial') {
        //     // Complex query needed
        // } elseif ($this->immunizationFilter === 'none') {
        //     $query->doesntHave('childVisits.immunizationActions');
        // }

        // Filter by age
        if ($this->ageFilter === 'under1') {
            $query->where('dob', '>=', now()->subYear());
        } elseif ($this->ageFilter === '1-2') {
            $query->whereBetween('dob', [now()->subYears(2), now()->subYear()]);
        } elseif ($this->ageFilter === 'over2') {
            $query->where('dob', '<', now()->subYears(2));
        }

        return $query->orderBy('dob', 'desc')->paginate($this->perPage);
    }

    public function render()
    {
        $children = $this->getChildren();

        // Calculate statistics
        $totalChildren = Child::count();

        // Get all children with immunization counts
        $childrenWithCounts = Child::with(['childVisits.immunizationActions' => function ($q) {
            $q->select('child_visit_id', 'vaccine_type');
        }])->get()->map(function ($child) {
            $uniqueVaccines = $child->childVisits->flatMap->immunizationActions->pluck('vaccine_type')->unique();
            $child->immunization_count = $uniqueVaccines->count();
            return $child;
        });

        $completeImmunization = $childrenWithCounts->where('immunization_count', '>=', 10)->count();
        $partialImmunization = $childrenWithCounts->whereBetween('immunization_count', [1, 9])->count();
        $noImmunization = $childrenWithCounts->where('immunization_count', 0)->count();

        return view('livewire.child-index', [
            'children' => $children,
            'stats' => [
                'total' => $totalChildren,
                'complete' => $completeImmunization,
                'partial' => $partialImmunization,
                'none' => $noImmunization,
            ]
        ]);
    }
}
