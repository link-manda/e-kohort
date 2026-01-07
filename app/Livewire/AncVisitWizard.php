<?php

namespace App\Livewire;

use App\Models\AncVisit;
use App\Models\Pregnancy;
use Carbon\Carbon;
use Livewire\Component;

class AncVisitWizard extends Component
{
    // Step Management
    public $currentStep = 1;
    public $totalSteps = 4;

    // Edit Mode
    public $visitId;
    public $isEditMode = false;
    public $originalVisitCode;

    // Pregnancy Data
    public $pregnancy_id;
    public $pregnancy;

    // Step 1: Basic Visit Info
    public $visit_date;
    public $gestational_age_weeks;
    public $chief_complaint;

    // Step 2: Physical Examination
    public $weight;
    public $height;
    public $lila;
    public $tfu;
    public $djj;
    public $fetal_presentation;

    // Step 3: Blood Pressure & MAP (Real-time calculation)
    public $systolic;
    public $diastolic;
    public $map_score;
    public $map_risk_level;

    // Step 4: Laboratory Results
    public $hb;
    public $protein_urine;
    public $blood_sugar;
    public $hiv_status = 'NR';
    public $syphilis_status = 'NR';
    public $hbsag_status = 'NR';
    public $tt_immunization;
    public $fe_tablets;
    public $diagnosis;
    public $referral_target;

    // Auto-generated
    public $risk_category;
    public $has_kek = false;
    public $has_anemia = false;

    protected function rules()
    {
        $rules = [
            'visit_date' => 'required|date|before_or_equal:today',
            'gestational_age_weeks' => 'required|integer|min:1|max:42',
        ];

        if ($this->currentStep >= 2) {
            $rules['weight'] = 'nullable|numeric|min:30|max:200';
            $rules['height'] = 'nullable|numeric|min:130|max:200';
            $rules['lila'] = 'nullable|numeric|min:15|max:50';
            $rules['tfu'] = 'nullable|numeric|min:10|max:50';
            $rules['djj'] = 'nullable|integer|min:100|max:180';
            $rules['fetal_presentation'] = 'nullable|string|max:50';
        }

        if ($this->currentStep >= 3) {
            $rules['systolic'] = 'required|integer|min:80|max:250';
            $rules['diastolic'] = 'required|integer|min:50|max:150';
        }

        if ($this->currentStep >= 4) {
            $rules['hb'] = 'nullable|numeric|min:5|max:20';
            $rules['protein_urine'] = 'nullable|string|max:10';
            $rules['blood_sugar'] = 'nullable|numeric|min:50|max:500';
            $rules['hiv_status'] = 'required|in:R,NR';
            $rules['syphilis_status'] = 'required|in:R,NR';
            $rules['hbsag_status'] = 'required|in:R,NR';
            $rules['tt_immunization'] = 'nullable|in:T1,T2,T3,T4,T5';
            $rules['fe_tablets'] = 'nullable|integer|min:0|max:200';
            $rules['diagnosis'] = 'nullable|string|max:500';
            $rules['referral_target'] = 'nullable|string|max:200';
        }

        return $rules;
    }

    public function mount($pregnancy_id, $visit_id = null)
    {
        $this->pregnancy_id = $pregnancy_id;
        $this->pregnancy = Pregnancy::with('patient')->findOrFail($pregnancy_id);

        // Check if edit mode
        if ($visit_id) {
            $this->isEditMode = true;
            $this->visitId = $visit_id;
            $this->loadVisitData($visit_id);
        } else {
            // Create mode - set defaults
            $this->visit_date = now()->format('Y-m-d');
            $this->gestational_age_weeks = $this->pregnancy->gestational_age;
        }
    }

    public function loadVisitData($visit_id)
    {
        $visit = AncVisit::findOrFail($visit_id);

        // Store original visit code (cannot be changed)
        $this->originalVisitCode = $visit->visit_code;

        // Load all visit data
        $this->visit_date = $visit->visit_date->format('Y-m-d');
        $this->gestational_age_weeks = $visit->gestational_age;
        $this->chief_complaint = $visit->anamnesis;

        // Physical examination
        $this->weight = $visit->weight;
        $this->height = $visit->height;
        $this->lila = $visit->lila;
        $this->tfu = $visit->fundal_height;
        $this->djj = $visit->fetal_heart_rate;
        $this->fetal_presentation = $visit->fetal_presentation;

        // Blood pressure
        $this->systolic = $visit->systolic;
        $this->diastolic = $visit->diastolic;
        $this->map_score = $visit->map_score;

        // Laboratory
        $this->hb = $visit->hb;
        $this->protein_urine = $visit->protein_urine;
        $this->blood_sugar = $visit->glucose_urine;
        $this->hiv_status = $visit->hiv_status;
        $this->syphilis_status = $visit->syphilis_status;
        $this->hbsag_status = $visit->hbsag_status;
        $this->tt_immunization = $visit->ttd_given ? 'T1' : null;
        $this->fe_tablets = $visit->fe_given ? 90 : 0;
        $this->diagnosis = $visit->clinical_notes;
        $this->referral_target = null;

        // Risk detection
        $this->has_kek = $visit->lila && $visit->lila < 23.5;
        $this->has_anemia = $visit->hb && $visit->hb < 11;
        $this->risk_category = $visit->risk_category;

        // Recalculate MAP
        $this->calculateMAP();
    }

    public function updated($propertyName)
    {
        // Real-time MAP calculation
        if (in_array($propertyName, ['systolic', 'diastolic'])) {
            $this->calculateMAP();
        }

        // Real-time KEK detection
        if ($propertyName === 'lila' && $this->lila) {
            $this->has_kek = $this->lila < 23.5;
        }

        // Real-time Anemia detection
        if ($propertyName === 'hb' && $this->hb) {
            $this->has_anemia = $this->hb < 11;
        }

        // Auto-update risk category
        $this->detectRiskCategory();
    }

    public function calculateMAP()
    {
        if ($this->systolic && $this->diastolic) {
            $this->map_score = round($this->diastolic + (($this->systolic - $this->diastolic) / 3), 2);

            if ($this->map_score > 100) {
                $this->map_risk_level = 'BAHAYA';
            } elseif ($this->map_score > 90) {
                $this->map_risk_level = 'WASPADA';
            } else {
                $this->map_risk_level = 'NORMAL';
            }
        }
    }

    public function detectRiskCategory()
    {
        $risks = [];

        if ($this->has_kek) $risks[] = 'KEK';
        if ($this->has_anemia) $risks[] = 'Anemia';
        if ($this->map_score && $this->map_score > 100) $risks[] = 'Hipertensi Berat';
        if ($this->hiv_status === 'R' || $this->syphilis_status === 'R' || $this->hbsag_status === 'R') {
            $risks[] = 'Triple Elimination';
        }

        if (count($risks) >= 3) {
            $this->risk_category = 'Ekstrem';
        } elseif (count($risks) >= 1) {
            $this->risk_category = 'Tinggi';
        } else {
            $this->risk_category = 'Rendah';
        }
    }

    public function nextStep()
    {
        $this->validate();

        if ($this->currentStep < $this->totalSteps) {
            $this->currentStep++;
        }
    }

    public function previousStep()
    {
        if ($this->currentStep > 1) {
            $this->currentStep--;
        }
    }

    public function save()
    {
        $this->validate();

        // Calculate trimester based on gestational age
        $trimester = $this->gestational_age_weeks <= 12 ? 1 : ($this->gestational_age_weeks <= 28 ? 2 : 3);

        $data = [
            'visit_date' => $this->visit_date,
            'trimester' => $trimester,
            'gestational_age' => $this->gestational_age_weeks,
            'weight' => $this->weight ?: null,
            'height' => $this->height ?: null,
            'lila' => $this->lila ?: null,
            'systolic' => $this->systolic,
            'diastolic' => $this->diastolic,
            'map_score' => $this->map_score,
            'fundal_height' => $this->tfu ?: null,
            'fetal_heart_rate' => $this->djj ?: null,
            'fetal_presentation' => $this->fetal_presentation ?: null,
            'hb' => $this->hb ?: null,
            'protein_urine' => $this->protein_urine ?: null,
            'glucose_urine' => $this->blood_sugar ?: null,
            'hiv_status' => $this->hiv_status,
            'syphilis_status' => $this->syphilis_status,
            'hbsag_status' => $this->hbsag_status,
            'ttd_given' => $this->tt_immunization ? true : false,
            'fe_given' => $this->fe_tablets && $this->fe_tablets > 0 ? true : false,
            'risk_category' => $this->risk_category,
            'anamnesis' => $this->chief_complaint ?: null,
            'clinical_notes' => $this->diagnosis ?: null,
        ];

        if ($this->isEditMode) {
            // Update existing visit
            $visit = AncVisit::findOrFail($this->visitId);
            $visit->update($data);

            session()->flash('success', 'Kunjungan ANC berhasil diperbarui');
            return redirect()->route('anc-visits.show', $this->visitId);
        } else {
            // Determine visit code based on existing visits
            $visitCount = AncVisit::where('pregnancy_id', $this->pregnancy_id)->count();
            $visitCodes = ['K1', 'K2', 'K3', 'K4', 'K5', 'K6'];
            $visitCode = $visitCodes[min($visitCount, 5)] ?? 'K6';

            $data['pregnancy_id'] = $this->pregnancy_id;
            $data['visit_code'] = $visitCode;

            AncVisit::create($data);

            session()->flash('success', 'Kunjungan ANC berhasil dicatat');
            return redirect()->route('patients.show', $this->pregnancy->patient_id);
        }
    }

    public function render()
    {
        return view('livewire.anc-visit-wizard');
    }
}
