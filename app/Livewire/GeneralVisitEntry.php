<?php

namespace App\Livewire;

use App\Models\GeneralVisit;
use App\Models\Patient;
use Livewire\Component;

class GeneralVisitEntry extends Component
{
    public $patient;
    public $visit_date;

    // Form Fields
    public $complaint;
    public $systolic;
    public $diastolic;
    public $temperature;
    public $weight;
    public $height;
    public $physical_exam;
    public $diagnosis;
    public $icd10_code;
    public $therapy;
    public $status = 'Pulang';
    public $payment_method = 'Umum';

    // Search ICD-10
    public $icd_search = '';
    public $icd_results = [];
    public $show_icd_dropdown = false;

    protected $rules = [
        'visit_date' => 'required|date',
        'complaint' => 'required|string',
        'systolic' => 'nullable|integer|min:0|max:300',
        'diastolic' => 'nullable|integer|min:0|max:200',
        'temperature' => 'nullable|numeric|min:30|max:45',
        'weight' => 'nullable|numeric|min:0|max:500',
        'height' => 'nullable|numeric|min:0|max:300',
        'physical_exam' => 'nullable|string',
        'diagnosis' => 'required|string',
        'icd10_code' => 'nullable|string',
        'therapy' => 'required|string',
        'status' => 'required|in:Pulang,Rujuk,Rawat Inap',
        'payment_method' => 'required|in:Umum,BPJS',
    ];

    public function mount($patient_id)
    {
        $this->patient = Patient::findOrFail($patient_id);
        $this->visit_date = now()->format('Y-m-d\TH:i');
    }

    public function updatedIcdSearch($value)
    {
        if (strlen($value) >= 2) {
            $this->icd_results = \App\Models\Icd10Code::search($value)->take(10)->get()->toArray();
            $this->show_icd_dropdown = true;
        } else {
            $this->icd_results = [];
            $this->show_icd_dropdown = false;
        }
    }

    public function selectIcd10($code, $name)
    {
        $this->icd10_code = $code;
        $this->diagnosis = $name; // Auto-fill diagnosis with ICD name if desired, or just append
        $this->icd_search = $code . ' - ' . $name;
        $this->show_icd_dropdown = false;
    }

    public function save()
    {
        $this->validate();

        GeneralVisit::create([
            'patient_id' => $this->patient->id,
            'visit_date' => $this->visit_date,
            'complaint' => $this->complaint,
            'systolic' => $this->systolic ?: null,
            'diastolic' => $this->diastolic ?: null,
            'temperature' => $this->temperature ?: null,
            'weight' => $this->weight ?: null,
            'height' => $this->height ?: null,
            'physical_exam' => $this->physical_exam,
            'diagnosis' => $this->diagnosis,
            'icd10_code' => $this->icd10_code,
            'therapy' => $this->therapy,
            'status' => $this->status,
            'payment_method' => $this->payment_method,
        ]);

        session()->flash('success', 'Kunjungan Umum berhasil disimpan.');

        // Redirect to patient detail or back to queue?
        // Usually back to patient detail or queue makes sense.
        return redirect()->route('patients.show', $this->patient->id);
    }

    public function render()
    {
        return view('livewire.general-visit-entry')->layout('layouts.dashboard');
    }
}
