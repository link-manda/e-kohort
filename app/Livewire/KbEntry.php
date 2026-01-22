<?php

namespace App\Livewire;

use App\Models\KbMethod;
use App\Models\KbVisit;
use App\Models\Patient;
use App\Services\KbSchedulingService;
use Livewire\Component;

class KbEntry extends Component
{
    // Patient & Visit Info
    public $patient_id;
    public $patient;
    public $patient_search = '';
    public $visit_date;
    public $visit_type = 'Peserta Baru';
    public $payment_type = 'Umum';

    // Physical Examination
    public $weight;
    public $blood_pressure_systolic;
    public $blood_pressure_diastolic;
    public $physical_exam_notes;

    // Contraception Method
    public $kb_method_id;
    public $contraception_brand;

    // Medical
    public $icd_code;
    public $diagnosis;

    // ICD-10 Search (typeahead)
    public $icd_search = '';
    public $icd_results = [];
    public $show_icd_dropdown = false;
    public $side_effects;
    public $complications;
    public $informed_consent = false;


    public function updated($propertyName)
    {
        if ($propertyName === 'icd_search') {
            $this->searchIcd10();
        }

    }

    /**
     * Livewire computed property for patient search results.
     * Access in blade as $patientResults
     */
    public function getPatientResultsProperty()
    {
        if (! $this->patient_search || strlen($this->patient_search) < 2) {
            return collect();
        }

        return Patient::query()
            ->where('name', 'like', '%'.$this->patient_search.'%')
            ->orWhere('no_rm', 'like', '%'.$this->patient_search.'%')
            ->orderBy('name')
            ->limit(10)
            ->get();
    }

    public function selectPatient(int $id)
    {
        $this->patient_id = $id;
        $this->patient = Patient::find($id);
        $this->patient_search = '';
    }

    // Scheduling
    public $next_visit_date;
    public $midwife_name;

    // UI State
    public $isHypertensive = false;
    public $selectedMethod;
    public $hypertensionWarning = '';

    protected $rules = [
        'patient_id' => 'required|exists:patients,id',
        'visit_date' => 'required|date',
        'visit_type' => 'required|in:Peserta Baru,Peserta Lama,Ganti Cara',
        'payment_type' => 'required|in:Umum,BPJS',
        'weight' => 'nullable|numeric|min:0',
        'blood_pressure_systolic' => 'nullable|integer|min:0|max:300',
        'blood_pressure_diastolic' => 'nullable|integer|min:0|max:200',
        'physical_exam_notes' => 'nullable|string|max:1000',
        'kb_method_id' => 'required|exists:kb_methods,id',
        'contraception_brand' => 'nullable|string|max:100',
        'icd_code' => 'nullable|string|max:20',
        'diagnosis' => 'nullable|string|max:255',
        'side_effects' => 'nullable|string|max:500',
        'complications' => 'nullable|string|max:500',
        'informed_consent' => 'boolean',
        'next_visit_date' => 'nullable|date|after:visit_date',
        'midwife_name' => 'required|string|max:100',
    ];

    public function mount()
    {
        $this->visit_date = now()->format('Y-m-d\TH:i');
        $this->midwife_name = auth()->user()->name;
    }

    /**
     * Search ICD-10 codes for KB entry (typeahead)
     */
    public function searchIcd10()
    {
        if (strlen($this->icd_search) < 2) {
            $this->icd_results = [];
            $this->show_icd_dropdown = false;
            return;
        }

        // Use Icd10Code model search similar to ImmunizationEntry
        $results = \App\Models\Icd10Code::search($this->icd_search);

        $this->icd_results = $results->map(function ($icd) {
            return [
                'code' => $icd->code,
                'name' => $icd->name,
                'description' => $icd->description,
            ];
        })->toArray();

        $this->show_icd_dropdown = count($this->icd_results) > 0;
    }

    public function selectIcd10($code)
    {
        $icdData = \App\Models\Icd10Code::where('code', $code)->first();

        if ($icdData) {
            $this->icd_code = $icdData->code;
            $this->diagnosis = $icdData->description;
            $this->icd_search = $icdData->code . ' - ' . $icdData->name;
            $this->show_icd_dropdown = false;
        }
    }

    public function clearIcd10()
    {
        $this->icd_code = '';
        $this->diagnosis = '';
        $this->icd_search = '';
        $this->icd_results = [];
        $this->show_icd_dropdown = false;
    }

    public function updatedPatientId()
    {
        $this->patient = Patient::find($this->patient_id);
    }

    public function updatedBloodPressureSystolic()
    {
        $this->checkHypertension();
    }

    public function updatedBloodPressureDiastolic()
    {
        $this->checkHypertension();
    }

    public function updatedKbMethodId()
    {
        $this->selectedMethod = KbMethod::find($this->kb_method_id);
        $this->validateMethodForHypertension();
        $this->calculateNextVisit();
    }

    public function updatedVisitDate()
    {
        $this->calculateNextVisit();
    }

    public function updatedVisitType()
    {
        $this->calculateNextVisit();
    }

    /**
     * Check if blood pressure indicates hypertension
     */
    private function checkHypertension()
    {
        $systolic = (int) $this->blood_pressure_systolic;
        $diastolic = (int) $this->blood_pressure_diastolic;

        $this->isHypertensive = ($systolic >= 140 || $diastolic >= 90);

        if ($this->isHypertensive) {
            $this->hypertensionWarning = 'HIPERTENSI! Sarankan IUD atau Implant non-hormonal (Standard WHO MEC)';
        } else {
            $this->hypertensionWarning = '';
        }

        $this->validateMethodForHypertension();
    }

    /**
     * Validate if selected method is safe for hypertension
     */
    private function validateMethodForHypertension()
    {
        if (!$this->selectedMethod || !$this->isHypertensive) {
            return;
        }

        if ($this->selectedMethod->is_hormonal) {
            $this->addError('kb_method_id', 'Metode hormonal tidak aman untuk pasien hipertensi. Pilih IUD atau metode non-hormonal lainnya.');
            $this->kb_method_id = null;
            $this->selectedMethod = null;
        }
    }

    /**
     * Calculate next visit date using scheduling service
     */
    private function calculateNextVisit()
    {
        if (!$this->kb_method_id || !$this->visit_date || !$this->patient_id) {
            return;
        }

        try {
            $tempVisit = new KbVisit([
                'patient_id' => $this->patient_id,
                'kb_method_id' => $this->kb_method_id,
                'visit_date' => $this->visit_date,
                'visit_type' => $this->visit_type,
            ]);

            $service = new KbSchedulingService();
            $nextDate = $service->calculateNextVisitDate($tempVisit);
            $this->next_visit_date = $nextDate->format('Y-m-d');
        } catch (\Exception $e) {
            // Silent fail, user can manually set date
        }
    }

    /**
     * Get available methods based on hypertension status
     * Computed property to avoid storing collections in public properties
     */
    public function getAvailableMethodsProperty()
    {
        $query = KbMethod::active();

        if ($this->isHypertensive) {
            // Only show non-hormonal methods for hypertensive patients
            $query->nonHormonal();
        }

        return $query->get()->groupBy('category');
    }

    /**
     * Save KB visit
     */
    public function save()
    {
        // Additional server-side validation for invasive methods
        $method = KbMethod::find($this->kb_method_id);

        if (!$method) {
            $this->addError('kb_method_id', 'Metode kontrasepsi tidak valid.');
            return;
        }

        // Hard block: Hormonal methods for hypertensive patients
        if ($this->isHypertensive && $method->is_hormonal) {
            $this->addError('kb_method_id', 'BLOKIR: Metode hormonal dilarang untuk pasien hipertensi (Tensi ≥140/90).');
            return;
        }

        // Require informed consent for invasive methods (IUD/Implant)
        if (in_array($method->category, ['IUD', 'IMPLANT']) && !$this->informed_consent) {
            $this->addError('informed_consent', 'Informed consent wajib untuk metode invasif (IUD/Implant).');
            return;
        }

        $validated = $this->validate();

        KbVisit::create($validated);

        session()->flash('success', 'Data KB berhasil disimpan!');
        $this->resetForm();
    }

    /**
     * Reset form after successful save
     */
    private function resetForm()
    {
        $this->reset([
            'patient_id',
            'patient',
            'weight',
            'blood_pressure_systolic',
            'blood_pressure_diastolic',
            'physical_exam_notes',
            'kb_method_id',
            'contraception_brand',
            'icd_code',
            'diagnosis',
            'side_effects',
            'complications',
            'informed_consent',
            'next_visit_date',
            'selectedMethod',
            'isHypertensive',
            'hypertensionWarning',
        ]);

        $this->visit_date = now()->format('Y-m-d\TH:i');
        $this->visit_type = 'Peserta Baru';
        $this->payment_type = 'Umum';
        $this->midwife_name = auth()->user()->name;
    }

    public function render()
    {
        return view('livewire.kb-entry', [
            'patients' => Patient::orderBy('name')->get(),
            'availableMethods' => $this->availableMethods,
            'patientResults' => $this->patientResults,
        ]);
    }
}
