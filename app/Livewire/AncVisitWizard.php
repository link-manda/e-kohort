<?php

namespace App\Livewire;

use App\Models\AncVisit;
use App\Models\Notification;
use App\Models\Pregnancy;
use Carbon\Carbon;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

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
    public $anc_12t = false;
    public $bmi;
    public $usg_check = false;
    public $counseling_check = false;
    public $risk_level;
    public $follow_up;
    public $midwife_name;
    public $service_fee;
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
            $rules['anc_12t'] = 'boolean';
            $rules['bmi'] = 'nullable|numeric|min:10|max:50';
            $rules['usg_check'] = 'boolean';
            $rules['counseling_check'] = 'boolean';
            $rules['risk_level'] = 'nullable|string|max:500';
            $rules['follow_up'] = 'nullable|string|max:500';
            $rules['midwife_name'] = 'nullable|string|max:200';
            $rules['service_fee'] = 'nullable|numeric|min:0';
        }

        return $rules;
    }

    protected function messages()
    {
        return [
            'visit_date.required' => 'Tanggal kunjungan harus diisi.',
            'visit_date.before_or_equal' => 'Tanggal kunjungan tidak boleh lebih dari hari ini.',
            'gestational_age_weeks.required' => 'Usia kehamilan harus diisi.',
            'gestational_age_weeks.min' => 'Usia kehamilan minimal 1 minggu.',
            'gestational_age_weeks.max' => 'Usia kehamilan maksimal 42 minggu.',

            'weight.min' => 'Berat badan minimal 30 kg.',
            'weight.max' => 'Berat badan maksimal 200 kg.',
            'height.min' => 'Tinggi badan minimal 130 cm.',
            'height.max' => 'Tinggi badan maksimal 200 cm.',
            'lila.min' => 'LILA minimal 15 cm.',
            'lila.max' => 'LILA maksimal 50 cm.',
            'tfu.min' => 'TFU minimal 10 cm (usia kehamilan < 12 minggu biasanya tidak terukur).',
            'tfu.max' => 'TFU maksimal 50 cm.',
            'djj.min' => 'DJJ minimal 100 bpm.',
            'djj.max' => 'DJJ maksimal 180 bpm.',

            'systolic.required' => 'Tekanan darah sistolik harus diisi.',
            'systolic.min' => 'Tekanan sistolik minimal 80 mmHg.',
            'systolic.max' => 'Tekanan sistolik maksimal 250 mmHg.',
            'diastolic.required' => 'Tekanan darah diastolik harus diisi.',
            'diastolic.min' => 'Tekanan diastolik minimal 50 mmHg.',
            'diastolic.max' => 'Tekanan diastolik maksimal 150 mmHg.',

            'hb.min' => 'Hemoglobin minimal 5 g/dL.',
            'hb.max' => 'Hemoglobin maksimal 20 g/dL.',
            'hiv_status.required' => 'Status HIV harus dipilih.',
            'syphilis_status.required' => 'Status Sifilis harus dipilih.',
            'hbsag_status.required' => 'Status HBsAg harus dipilih.',
            'fe_tablets.min' => 'Jumlah tablet Fe tidak boleh negatif.',
            'fe_tablets.max' => 'Jumlah tablet Fe maksimal 200.',
            'service_fee.min' => 'Biaya layanan tidak boleh negatif.',
        ];
    }

    public function mount($pregnancy_id, $visit_id = null)
    {
        $this->pregnancy_id = $pregnancy_id;
        $this->pregnancy = Pregnancy::with('patient')->findOrFail($pregnancy_id);

        // Check if pregnancy is still active (not delivered)
        if ($this->pregnancy->status === 'Lahir') {
            session()->flash('error', 'Tidak dapat menambah kunjungan ANC karena kehamilan sudah selesai (sudah lahir). Silakan lakukan kunjungan nifas.');
            return redirect()->route('patients.show', $this->pregnancy->patient_id);
        }

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
        $this->chief_complaint = null; // Not stored in database

        // Physical examination
        $this->weight = $visit->weight;
        $this->height = $visit->height;
        $this->lila = $visit->lila;
        $this->tfu = $visit->tfu;
        $this->djj = $visit->djj;
        $this->fetal_presentation = $visit->fetal_presentation;
        $this->bmi = $visit->bmi;

        // Blood pressure
        $this->systolic = $visit->systolic;
        $this->diastolic = $visit->diastolic;
        $this->map_score = $visit->map_score;

        // Laboratory
        $this->hb = $visit->hb;
        $this->protein_urine = $visit->protein_urine;
        $this->blood_sugar = $visit->blood_sugar;
        $this->hiv_status = $visit->hiv_status;
        $this->syphilis_status = $visit->syphilis_status;
        $this->hbsag_status = $visit->hbsag_status;
        $this->tt_immunization = $visit->tt_immunization;
        $this->fe_tablets = $visit->fe_tablets;

        // New fields
        $this->anc_12t = $visit->anc_12t ?? false;
        $this->usg_check = $visit->usg_check ?? false;
        $this->counseling_check = $visit->counseling_check ?? false;
        $this->risk_level = $visit->risk_level;
        $this->follow_up = $visit->follow_up;
        $this->midwife_name = $visit->midwife_name;
        $this->service_fee = $visit->service_fee;

        $this->diagnosis = $visit->diagnosis;
        $this->referral_target = $visit->referral_target;

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

        // Real-time BMI calculation
        if (in_array($propertyName, ['weight', 'height'])) {
            $this->calculateBMI();
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

    public function calculateBMI()
    {
        if ($this->weight && $this->height) {
            // Convert height from cm to meters
            $heightInMeters = $this->height / 100;
            // BMI = weight (kg) / height (m)²
            $this->bmi = round($this->weight / ($heightInMeters * $heightInMeters), 1);
        } else {
            $this->bmi = null;
        }
    }

    public function getBMICategory()
    {
        if (!$this->bmi) {
            return '';
        }

        if ($this->bmi < 18.5) {
            return 'Underweight';
        } elseif ($this->bmi < 25) {
            return 'Normal';
        } elseif ($this->bmi < 30) {
            return 'Overweight';
        } else {
            return 'Obesitas';
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
        try {
            $this->validate();

            // Calculate trimester based on gestational age
            $trimester = $this->gestational_age_weeks <= 12 ? 1 : ($this->gestational_age_weeks <= 28 ? 2 : 3);

            $data = [
                'visit_date' => $this->visit_date,
                'trimester' => $trimester,
                'anc_12t' => (bool) $this->anc_12t,
                'gestational_age' => (int) $this->gestational_age_weeks,
                'weight' => $this->weight ? (float) $this->weight : null,
                'height' => $this->height ? (float) $this->height : null,
                'lila' => $this->lila ? (float) $this->lila : null,
                'bmi' => $this->bmi ? (float) $this->bmi : null,
                'systolic' => (int) $this->systolic,
                'diastolic' => (int) $this->diastolic,
                'map_score' => $this->map_score ? (float) $this->map_score : null,
                'tfu' => $this->tfu ? (float) $this->tfu : null,
                'djj' => $this->djj ? (int) $this->djj : null,
                'fetal_presentation' => $this->fetal_presentation ?: null,
                'usg_check' => (bool) $this->usg_check,
                'counseling_check' => (bool) $this->counseling_check,
                'hb' => $this->hb ? (float) $this->hb : null,
                'protein_urine' => $this->protein_urine ?: null,
                'blood_sugar' => $this->blood_sugar ? (float) $this->blood_sugar : null,
                'hiv_status' => $this->hiv_status,
                'syphilis_status' => $this->syphilis_status,
                'hbsag_status' => $this->hbsag_status,
                'tt_immunization' => $this->tt_immunization ?: null,
                'fe_tablets' => $this->fe_tablets ? (int) $this->fe_tablets : null,
                'risk_category' => $this->risk_category,
                'risk_level' => $this->risk_level ?: null,
                'diagnosis' => $this->diagnosis ?: null,
                'referral_target' => $this->referral_target ?: null,
                'follow_up' => $this->follow_up ?: null,
                'midwife_name' => $this->midwife_name ?: null,
                'service_fee' => $this->service_fee ?: null,
            ];

            if ($this->isEditMode) {
                // Update existing visit - preserve original visit_code
                $visit = AncVisit::findOrFail($this->visitId);
                $data['visit_code'] = $this->originalVisitCode; // Preserve original visit code
                $visit->update($data);

                session()->flash('success', 'Kunjungan ANC berhasil diperbarui');
                return redirect()->route('anc-visits.show', $this->visitId);
            } else {
                // Determine visit code based on existing visits
                $visitCount = AncVisit::where('pregnancy_id', $this->pregnancy_id)->count();
                $visitCodes = ['K1', 'K2', 'K3', 'K4', 'K5', 'K6', 'K7', 'K8'];
                $visitCode = $visitCodes[min($visitCount, 7)] ?? 'K8';

                $data['pregnancy_id'] = $this->pregnancy_id;
                $data['visit_code'] = $visitCode;

                $visit = AncVisit::create($data);

                // Create notifications for high-risk conditions
                $this->createNotificationsIfNeeded($visit);

                session()->flash('success', 'Kunjungan ANC berhasil dicatat');
                return redirect()->route('patients.show', $this->pregnancy->patient_id);
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Validation errors are automatically handled by Livewire
            throw $e;
        } catch (\Exception $e) {
            // Log the error and show a user-friendly message
            \Log::error('ANC Visit Save Error: ' . $e->getMessage(), [
                'user_id' => auth()->id(),
                'pregnancy_id' => $this->pregnancy_id,
                'data' => $data ?? [],
                'trace' => $e->getTraceAsString()
            ]);

            session()->flash('error', 'Terjadi kesalahan saat menyimpan data. Silakan coba lagi.');
            $this->dispatch('show-toast', ['message' => 'Gagal menyimpan kunjungan ANC', 'type' => 'error']);

            // Reset loading state
            $this->dispatch('loading-finished');
            return;
        }
    }

    private function createNotificationsIfNeeded(AncVisit $visit)
    {
        $patient = $visit->pregnancy->patient;
        $user = Auth::user();

        // 1. Check for High Risk (MAP > 90)
        if ($visit->map_score >= 90) {
            $riskLevel = $visit->map_score >= 100 ? 'BAHAYA (MAP ≥ 100)' : 'WASPADA (MAP ≥ 90)';

            Notification::createHighRiskAlert($user, $patient, $visit, $riskLevel);
        }

        // 2. Check for Triple Eliminasi Reaktif
        $hasReaktif = $visit->hiv_status === 'Reaktif' ||
            $visit->syphilis_status === 'Reaktif' ||
            $visit->hbsag_status === 'Reaktif';

        if ($hasReaktif) {
            Notification::createTripleEliminasiAlert($user, $patient, $visit);
        }

        // 3. Check for KEK (LILA < 23.5)
        if ($visit->lila && $visit->lila < 23.5) {
            Notification::create([
                'user_id' => $user->id,
                'type' => 'high_risk',
                'title' => '⚠️ KEK Terdeteksi',
                'message' => "Pasien {$patient->nama_lengkap} memiliki LILA {$visit->lila} cm (KEK)",
                'link' => route('patients.show', $patient->id),
                'patient_id' => $patient->id,
                'anc_visit_id' => $visit->id,
            ]);
        }

        // 4. Check for Anemia (Hb < 11)
        if ($visit->hb && $visit->hb < 11) {
            Notification::create([
                'user_id' => $user->id,
                'type' => 'high_risk',
                'title' => '⚠️ Anemia Terdeteksi',
                'message' => "Pasien {$patient->nama_lengkap} memiliki Hb {$visit->hb} g/dL (Anemia)",
                'link' => route('patients.show', $patient->id),
                'patient_id' => $patient->id,
                'anc_visit_id' => $visit->id,
            ]);
        }

        // Dispatch event to refresh notification bell
        $this->dispatch('notification-created');
    }

    public function render()
    {
        return view('livewire.anc-visit-wizard');
    }

    public function showToast($message, $type = 'success')
    {
        $this->dispatch('show-toast', ['message' => $message, 'type' => $type]);
    }
}