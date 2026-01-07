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

    public function mount($pregnancy_id)
    {
        $this->pregnancy_id = $pregnancy_id;
        $this->pregnancy = Pregnancy::with('patient')->findOrFail($pregnancy_id);
        $this->visit_date = now()->format('Y-m-d');
        $this->gestational_age_weeks = $this->pregnancy->gestational_age;
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

        // Determine visit code based on existing visits
        $visitCount = AncVisit::where('pregnancy_id', $this->pregnancy_id)->count();
        $visitCodes = ['K1', 'K2', 'K3', 'K4', 'K5', 'K6'];
        $visitCode = $visitCodes[min($visitCount, 5)] ?? 'K6';

        AncVisit::create([
            'pregnancy_id' => $this->pregnancy_id,
            'visit_date' => $this->visit_date,
            'trimester' => $trimester,
            'visit_code' => $visitCode,
            'gestational_age' => $this->gestational_age_weeks,
            'weight' => $this->weight ?: null,
            'height' => $this->height ?: null,
            'lila' => $this->lila ?: null,
            'systolic' => $this->systolic,
            'diastolic' => $this->diastolic,
            'map_score' => $this->map_score,
            'tfu' => $this->tfu ?: null,
            'djj' => $this->djj ?: null,
            'hb' => $this->hb ?: null,
            'protein_urine' => $this->protein_urine ?: null,
            'hiv_status' => $this->hiv_status,
            'syphilis_status' => $this->syphilis_status,
            'hbsag_status' => $this->hbsag_status,
            'tt_immunization' => $this->tt_immunization ?: null,
            'fe_tablets' => $this->fe_tablets ?: null,
            'risk_category' => $this->risk_category,
            'diagnosis' => $this->diagnosis ?: null,
            'referral_target' => $this->referral_target ?: null,
        ]);

        session()->flash('success', 'Kunjungan ANC berhasil dicatat');
        return redirect()->route('patients.show', $this->pregnancy->patient_id);
    }

    public function render()
    {
        return view('livewire.anc-visit-wizard');
    }
}
