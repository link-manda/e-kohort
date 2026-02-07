<?php

namespace App\Livewire;

use App\Models\Patient;
use App\Models\Child;
use App\DTOs\UnifiedPatientDTO;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class PatientList extends Component
{
    use WithPagination;

    public $search = '';
    public $categoryFilter = 'all'; // all, Umum, Bumil, Bayi/Balita, Lansia
    public $genderFilter = 'all'; // all, L, P
    public $perPage = 20;

    protected $queryString = [
        'search' => ['except' => ''],
        'categoryFilter' => ['except' => 'all'],
        'genderFilter' => ['except' => 'all'],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingCategoryFilter()
    {
        $this->resetPage();
    }

    public function updatingGenderFilter()
    {
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->search = '';
        $this->categoryFilter = 'all';
        $this->genderFilter = 'all';
        $this->resetPage();
    }

    /**
     * Get unified patient data (patients + children)
     */
    public function getUnifiedData(): LengthAwarePaginator
    {
        $patients = collect();
        $children = collect();

        // Determine what data to fetch based on category filter
        $includePatients = $this->categoryFilter === 'all' || $this->categoryFilter !== 'Bayi/Balita';
        $includeChildren = $this->categoryFilter === 'all' || $this->categoryFilter === 'Bayi/Balita';

        // Fetch Patients (excluding Bayi/Balita when filter is specific)
        if ($includePatients) {
            $patientQuery = Patient::query();

            if ($this->search) {
                $patientQuery->where(function ($q) {
                    $q->where('nik', 'like', '%' . $this->search . '%')
                        ->orWhere('name', 'like', '%' . $this->search . '%')
                        ->orWhere('phone', 'like', '%' . $this->search . '%');
                });
            }

            if ($this->categoryFilter !== 'all' && $this->categoryFilter !== 'Bayi/Balita') {
                $patientQuery->where('category', $this->categoryFilter);
            }

            if ($this->genderFilter !== 'all') {
                $patientQuery->where('gender', $this->genderFilter);
            }

            $patients = $patientQuery->get()->map(fn($p) => UnifiedPatientDTO::fromPatient($p));
        }

        // Fetch Children (for Bayi/Balita)
        if ($includeChildren) {
            $childQuery = Child::query()->with('patient');

            if ($this->search) {
                $childQuery->where(function ($q) {
                    $q->where('nik', 'like', '%' . $this->search . '%')
                        ->orWhere('name', 'like', '%' . $this->search . '%')
                        ->orWhere('parent_phone', 'like', '%' . $this->search . '%')
                        ->orWhereHas('patient', function ($pq) {
                            $pq->where('phone', 'like', '%' . $this->search . '%')
                                ->orWhere('name', 'like', '%' . $this->search . '%');
                        });
                });
            }

            if ($this->genderFilter !== 'all') {
                $childQuery->where('gender', $this->genderFilter);
            }

            $children = $childQuery->get()->map(fn($c) => UnifiedPatientDTO::fromChild($c));
        }

        // Merge and sort by created_at desc
        $merged = $patients->concat($children)
            ->sortByDesc('created_at')
            ->values();

        // Manual pagination using Livewire page
        $page = $this->paginators['page'] ?? 1;
        $offset = ($page - 1) * $this->perPage;
        $items = $merged->slice($offset, $this->perPage)->values();

        return new LengthAwarePaginator(
            $items,
            $merged->count(),
            $this->perPage,
            $page,
            ['path' => url()->current(), 'pageName' => 'page']
        );
    }

    /**
     * Calculate statistics including children
     */
    public function getStats(): array
    {
        $patientCount = Patient::count();
        $childCount = Child::count();

        return [
            'total' => $patientCount + $childCount,
            'categories' => [
                'Umum' => Patient::where('category', 'Umum')->count(),
                'Bumil' => Patient::where('category', 'Bumil')->count(),
                'Bayi/Balita' => $childCount, // Now shows actual children count
                'Lansia' => Patient::where('category', 'Lansia')->count(),
            ],
            'genders' => [
                'L' => Patient::where('gender', 'L')->count() + Child::where('gender', 'L')->count(),
                'P' => Patient::where('gender', 'P')->count() + Child::where('gender', 'P')->count(),
            ],
        ];
    }

    public function render()
    {
        $patients = $this->getUnifiedData();
        $stats = $this->getStats();

        return view('livewire.patient-list', [
            'patients' => $patients,
            'stats' => $stats,
        ])->layout('layouts.dashboard');
    }
}
