<?php

namespace App\Livewire;

use App\Models\KbVisit;
use App\Models\KbMethod;
use Livewire\Component;
use Livewire\WithPagination;

class KbIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $start_date;
    public $end_date;
    public $visit_type = '';
    public $payment_type = '';
    public $kb_method_id = '';
    public $is_hypertensive = '';

    protected $paginationTheme = 'tailwind';

    protected $updatesQueryString = ['search', 'start_date', 'end_date', 'visit_type', 'payment_type', 'kb_method_id', 'is_hypertensive'];

    public function mount()
    {
        $this->start_date = now()->startOfMonth()->toDateString();
        $this->end_date = now()->endOfMonth()->toDateString();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function openPatientPreview($patientId)
    {
        // Relay event to PatientPreview component
        $this->dispatch('openPatientPreview', (int) $patientId);
    }

    public function resetFilters()
    {
        $this->reset(['search','start_date','end_date','visit_type','payment_type','kb_method_id','is_hypertensive']);
        $this->mount();
    }

    public function render()
    {
        $methods = KbMethod::active()->orderBy('name')->get();

        $query = KbVisit::with(['patient','kbMethod'])
            ->when($this->search, fn($q) => $q->whereHas('patient', fn($q2) => $q2->where('name','like','%'.$this->search.'%')->orWhere('no_rm','like','%'.$this->search.'%')))
            ->when($this->start_date, fn($q) => $q->whereDate('visit_date', '>=', $this->start_date))
            ->when($this->end_date, fn($q) => $q->whereDate('visit_date', '<=', $this->end_date))
            ->when($this->visit_type, fn($q) => $q->where('visit_type', $this->visit_type))
            ->when($this->payment_type, fn($q) => $q->where('payment_type', $this->payment_type))
            ->when($this->kb_method_id, fn($q) => $q->where('kb_method_id', $this->kb_method_id))
            ->when($this->is_hypertensive !== '', fn($q) => $q->where(function($qq) {
                $qq->where('blood_pressure_systolic','>=',140)->orWhere('blood_pressure_diastolic','>=',90);
            }))
            ->orderBy('visit_date','desc');

        $visits = $query->paginate(15);

        // Simple stats for cards
        $stats = [
            'total' => KbVisit::count(),
            'hypertensive' => KbVisit::where(function($q){
                $q->where('blood_pressure_systolic','>=',140)->orWhere('blood_pressure_diastolic','>=',90);
            })->count(),
            'normal' => 0,
        ];
        $stats['normal'] = $stats['total'] - $stats['hypertensive'];

        return view('livewire.kb-index', [
            'visits' => $visits,
            'methods' => $methods,
            'stats' => $stats,
        ]);
    }
}
