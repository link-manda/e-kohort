<?php

namespace App\Livewire;

use App\Models\Patient;
use App\Models\Pregnancy;
use Livewire\Component;
use Carbon\Carbon;

class PregnancyRegistration extends Component
{
    public $patient_id;
    public $patient;

    // Form fields
    public $gravida_g = 1;
    public $gravida_p = 0;
    public $gravida_a = 0;
    public $hpht;
    public $hpl;
    public $pregnancy_gap;
    public $risk_score_initial;

    protected $rules = [
        'gravida_g' => 'required|integer|min:1|max:20',
        'gravida_p' => 'required|integer|min:0|max:20',
        'gravida_a' => 'required|integer|min:0|max:20',
        'hpht' => 'required|date|before_or_equal:today',
        'hpl' => 'required|date|after:hpht',
        'pregnancy_gap' => 'nullable|integer|min:0|max:50',
        'risk_score_initial' => 'nullable|integer|min:0|max:50',
    ];

    public function mount($patient_id)
    {
        $this->patient_id = $patient_id;
        $this->patient = Patient::findOrFail($patient_id);

        // Set default HPHT to today
        $this->hpht = now()->format('Y-m-d');
        $this->calculateHPL();
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);

        // Auto-calculate HPL when HPHT changes
        if ($propertyName === 'hpht') {
            $this->calculateHPL();
        }
    }

    public function calculateHPL()
    {
        if ($this->hpht) {
            try {
                $hpht = Carbon::parse($this->hpht);
                $this->hpl = $hpht->copy()->addMonths(9)->format('Y-m-d');
            } catch (\Exception $e) {
                $this->hpl = '';
            }
        }
    }

    public function save()
    {
        $this->validate();

        // Check if patient already has active pregnancy
        if ($this->patient->activePregnancy) {
            session()->flash('error', 'Pasien sudah memiliki kehamilan aktif!');
            return;
        }

        $gravida = "G{$this->gravida_g}P{$this->gravida_p}A{$this->gravida_a}";

        Pregnancy::create([
            'patient_id' => $this->patient_id,
            'gravida' => $gravida,
            'hpht' => $this->hpht,
            'hpl' => $this->hpl,
            'pregnancy_gap' => $this->pregnancy_gap ?: null,
            'risk_score_initial' => $this->risk_score_initial ?: null,
            'status' => 'Aktif',
        ]);

        session()->flash('success', 'Data kehamilan berhasil didaftarkan!');

        return redirect()->route('patients.show', $this->patient_id);
    }

    public function render()
    {
        return view('livewire.pregnancy-registration');
    }
}
