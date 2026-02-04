<?php

namespace App\Livewire;

use App\Models\GeneralVisit;
use Livewire\Component;
use Livewire\WithPagination;

class GeneralVisitList extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = 'all'; // all, Pulang, Rujuk, Rawat Inap
    public $paymentFilter = 'all'; // all, Umum, BPJS
    public $dateFrom = '';
    public $dateTo = '';
    public $perPage = 20;

    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => 'all'],
        'paymentFilter' => ['except' => 'all'],
    ];

    public function mount()
    {
        // Default date range: today
        $this->dateFrom = now()->format('Y-m-d');
        $this->dateTo = now()->format('Y-m-d');
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function updatingPaymentFilter()
    {
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->search = '';
        $this->statusFilter = 'all';
        $this->paymentFilter = 'all';
        $this->dateFrom = now()->format('Y-m-d');
        $this->dateTo = now()->format('Y-m-d');
        $this->resetPage();
    }

    public function getVisits()
    {
        $query = GeneralVisit::with('patient');

        // Date range filter
        if ($this->dateFrom) {
            $query->whereDate('visit_date', '>=', $this->dateFrom);
        }
        if ($this->dateTo) {
            $query->whereDate('visit_date', '<=', $this->dateTo);
        }

        // Search by patient name or NIK
        if ($this->search) {
            $query->whereHas('patient', function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('nik', 'like', '%' . $this->search . '%');
            });
        }

        // Status filter
        if ($this->statusFilter !== 'all') {
            $query->where('status', $this->statusFilter);
        }

        // Payment method filter
        if ($this->paymentFilter !== 'all') {
            $query->where('payment_method', $this->paymentFilter);
        }

        return $query->orderBy('visit_date', 'desc')->paginate($this->perPage);
    }

    public function render()
    {
        $visits = $this->getVisits();

        // Statistics
        $totalVisits = GeneralVisit::count();
        $todayVisits = GeneralVisit::whereDate('visit_date', today())->count();
        $statusStats = [
            'Pulang' => GeneralVisit::where('status', 'Pulang')->count(),
            'Rujuk' => GeneralVisit::where('status', 'Rujuk')->count(),
            'Rawat Inap' => GeneralVisit::where('status', 'Rawat Inap')->count(),
        ];

        return view('livewire.general-visit-list', [
            'visits' => $visits,
            'stats' => [
                'total' => $totalVisits,
                'today' => $todayVisits,
                'status' => $statusStats,
            ]
        ])->layout('layouts.dashboard');
    }
}

