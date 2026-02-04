<?php

namespace App\Livewire;

use App\Models\Patient;
use Livewire\Component;
use Livewire\WithPagination;

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

    public function getPatients()
    {
        $query = Patient::query();

        // Search by NIK, Name, or Phone
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('nik', 'like', '%' . $this->search . '%')
                    ->orWhere('name', 'like', '%' . $this->search . '%')
                    ->orWhere('phone', 'like', '%' . $this->search . '%');
            });
        }

        // Filter by category
        if ($this->categoryFilter !== 'all') {
            $query->where('category', $this->categoryFilter);
        }

        // Filter by gender
        if ($this->genderFilter !== 'all') {
            $query->where('gender', $this->genderFilter);
        }

        return $query->orderBy('created_at', 'desc')->paginate($this->perPage);
    }

    public function render()
    {
        $patients = $this->getPatients();

        // Calculate statistics for filters
        $totalPatients = Patient::count();
        $categoryStats = [
            'Umum' => Patient::where('category', 'Umum')->count(),
            'Bumil' => Patient::where('category', 'Bumil')->count(),
            'Bayi/Balita' => Patient::where('category', 'Bayi/Balita')->count(),
            'Lansia' => Patient::where('category', 'Lansia')->count(),
        ];
        $genderStats = [
            'L' => Patient::where('gender', 'L')->count(),
            'P' => Patient::where('gender', 'P')->count(),
        ];

        return view('livewire.patient-list', [
            'patients' => $patients,
            'stats' => [
                'total' => $totalPatients,
                'categories' => $categoryStats,
                'genders' => $genderStats,
            ]
        ])->layout('layouts.dashboard');
    }
}
