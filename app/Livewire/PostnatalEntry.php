<?php

namespace App\Livewire;

use App\Models\Pregnancy;
use App\Models\PostnatalVisit;
use Livewire\Component;

class PostnatalEntry extends Component
{
    public $pregnancy;
    public $postnatal_visit_id = null;
    public $isEditMode = false;
    public $errorMessage;

    // Form fields
    public $visit_date = '';
    public $visit_code = '';
    public $td_systolic = '';
    public $td_diastolic = '';
    public $temperature = '';
    public $lochea = '';
    public $uterine_involution = '';
    public $vitamin_a = false;
    public $fe_tablets = 0;
    public $complication_check = false;
    public $conclusion = '';

    // UI state
    public $showSuccess = false;
    public $visit_date_warning = '';
    public $visit_date_is_valid = false;
    public $showSuccessModal = false;
    public $existingVisits;

    // Real-time validation feedback
    public $blood_pressure_status = '';
    public $blood_pressure_category = '';
    public $temperature_status = '';
    public $temperature_category = '';

    protected function rules()
    {
        return [
            'visit_date' => 'required|date',
            'visit_code' => 'required|in:KF1,KF2,KF3,KF4',
            'td_systolic' => 'nullable|integer|min:80|max:250',
            'td_diastolic' => 'required|integer|min:50|max:150',
            'temperature' => 'nullable|numeric|min:35|max:42',
            'lochea' => 'nullable|in:Rubra,Sanguinolenta,Serosa,Alba',
            'uterine_involution' => 'nullable|string|max:255',
            'vitamin_a' => 'boolean',
            'fe_tablets' => 'nullable|integer|min:0|max:200',
            'complication_check' => 'boolean',
            'conclusion' => 'nullable|string',
        ];
    }

    protected $messages = [
        'visit_date.required' => 'Tanggal kunjungan wajib diisi',
        'visit_code.required' => 'Kode kunjungan wajib dipilih',
        'td_systolic.min' => 'Tekanan sistolik minimal 80 mmHg',
        'td_systolic.max' => 'Tekanan sistolik maksimal 250 mmHg',
        'td_diastolic.required' => 'Tekanan diastolik wajib diisi',
        'td_diastolic.min' => 'Tekanan diastolik minimal 50 mmHg',
        'td_diastolic.max' => 'Tekanan diastolik maksimal 150 mmHg',
        'temperature.min' => 'Suhu minimal 35°C',
        'temperature.max' => 'Suhu maksimal 42°C',
    ];

    public function mount(Pregnancy $pregnancy, $postnatal_visit_id = null)
    {
        $this->pregnancy = $pregnancy;

        // Check for error message from session
        $this->errorMessage = session('error');

        // Check if pregnancy is delivered
        if ($this->pregnancy->status !== 'Lahir') {
            $this->errorMessage = 'Kunjungan nifas hanya dapat dilakukan setelah persalinan tercatat.';
            return redirect()->route('patients.show', $pregnancy->patient_id);
        }

        // Edit mode
        if ($postnatal_visit_id) {
            $this->isEditMode = true;
            $this->postnatal_visit_id = $postnatal_visit_id;
            $this->loadVisitData($postnatal_visit_id);
        } else {
            // Create mode - set defaults
            $this->visit_date = now()->format('Y-m-d');
            $this->checkVisitDateValidity();
        }
    }

    public function loadVisitData($visit_id)
    {
        $visit = PostnatalVisit::findOrFail($visit_id);

        $this->visit_date = $visit->visit_date->format('Y-m-d');
        $this->visit_code = $visit->visit_code;
        $this->td_systolic = $visit->td_systolic;
        $this->td_diastolic = $visit->td_diastolic;
        $this->temperature = $visit->temperature;
        $this->lochea = $visit->lochea;
        $this->uterine_involution = $visit->uterine_involution;
        $this->vitamin_a = $visit->vitamin_a;
        $this->fe_tablets = $visit->fe_tablets;
        $this->complication_check = $visit->complication_check;
        $this->conclusion = $visit->conclusion;
    }

    public function updatedVisitCode()
    {
        $this->checkVisitDateValidity();
    }

    public function updated($propertyName)
    {
        // Real-time blood pressure validation
        if (in_array($propertyName, ['td_systolic', 'td_diastolic'])) {
            $this->validateBloodPressure();
        }

        // Real-time temperature validation
        if ($propertyName === 'temperature') {
            $this->validateTemperature();
        }
    }

    public function validateBloodPressure()
    {
        if ($this->td_systolic && $this->td_diastolic) {
            $systolic = (int) $this->td_systolic;
            $diastolic = (int) $this->td_diastolic;

            // Check for hypertension
            if ($systolic >= 140 || $diastolic >= 90) {
                $this->blood_pressure_category = 'danger';
                if ($systolic >= 160 || $diastolic >= 110) {
                    $this->blood_pressure_status = '⚠️ BAHAYA! Hipertensi Berat - Rujuk segera ke fasilitas kesehatan!';
                } else {
                    $this->blood_pressure_status = '⚠️ WASPADA! Hipertensi Ringan - Monitoring ketat diperlukan';
                }
            }
            // Check for hypotension
            elseif ($systolic < 90 || $diastolic < 60) {
                $this->blood_pressure_category = 'warning';
                $this->blood_pressure_status = '⚠️ Tekanan darah rendah - Perhatikan tanda-tanda syok';
            }
            // Normal
            else {
                $this->blood_pressure_category = 'normal';
                $this->blood_pressure_status = '✓ Tekanan darah normal (Sistol: 90-139, Diastol: 60-89 mmHg)';
            }
        } else {
            $this->blood_pressure_status = '';
            $this->blood_pressure_category = '';
        }
    }

    public function validateTemperature()
    {
        if ($this->temperature) {
            $temp = (float) $this->temperature;

            if ($temp >= 38) {
                $this->temperature_category = 'danger';
                $this->temperature_status = '⚠️ Demam terdeteksi! Waspadai infeksi nifas';
            } elseif ($temp >= 37.5 && $temp < 38) {
                $this->temperature_category = 'warning';
                $this->temperature_status = '⚠️ Suhu sedikit meningkat (subfebris)';
            } elseif ($temp < 36) {
                $this->temperature_category = 'warning';
                $this->temperature_status = '⚠️ Suhu tubuh rendah (hipotermia)';
            } else {
                $this->temperature_category = 'normal';
                $this->temperature_status = '✓ Suhu tubuh normal (36-37.4°C)';
            }
        } else {
            $this->temperature_status = '';
            $this->temperature_category = '';
        }
    }

    public function editVisit($visitId)
    {
        $visit = PostnatalVisit::findOrFail($visitId);

        // Pastikan visit milik pregnancy yang benar
        if ($visit->pregnancy_id !== $this->pregnancy->id) {
            session()->flash('error', 'Tidak dapat mengedit kunjungan ini.');
            return;
        }

        $this->isEditMode = true;
        $this->postnatal_visit_id = $visitId;
        $this->loadVisitData($visitId);
    }

    public function checkVisitDateValidity()
    {
        if (!$this->visit_date || !$this->visit_code || !$this->pregnancy->delivery_date) {
            $this->visit_date_warning = '';
            $this->visit_date_is_valid = false;
            return;
        }

        $deliveryDate = $this->pregnancy->delivery_date;
        $visitDate = \Carbon\Carbon::parse($this->visit_date);
        $daysSinceDelivery = $deliveryDate->diffInDays($visitDate);

        $validRanges = [
            'KF1' => ['min' => 0, 'max' => 2, 'label' => '6 jam - 2 hari'],
            'KF2' => ['min' => 3, 'max' => 7, 'label' => '3 - 7 hari'],
            'KF3' => ['min' => 8, 'max' => 28, 'label' => '8 - 28 hari'],
            'KF4' => ['min' => 29, 'max' => 42, 'label' => '29 - 42 hari'],
        ];

        $range = $validRanges[$this->visit_code] ?? null;

        if ($range) {
            if ($daysSinceDelivery < $range['min'] || $daysSinceDelivery > $range['max']) {
                $this->visit_date_warning = "⚠️ Tanggal kunjungan tidak sesuai jadwal {$this->visit_code} ({$range['label']} pasca persalinan). Saat ini: {$daysSinceDelivery} hari.";
                $this->visit_date_is_valid = false;
            } else {
                $this->visit_date_warning = "✅ Tanggal kunjungan sesuai jadwal {$this->visit_code}.";
                $this->visit_date_is_valid = true;
            }
        } else {
            $this->visit_date_is_valid = false;
        }
    }

    public function save()
    {
        $this->validate();

        $data = [
            'pregnancy_id' => $this->pregnancy->id,
            'visit_date' => $this->visit_date,
            'visit_code' => $this->visit_code,
            'td_systolic' => $this->td_systolic ?: null,
            'td_diastolic' => $this->td_diastolic ?: null,
            'temperature' => $this->temperature ?: null,
            'lochea' => $this->lochea,
            'uterine_involution' => $this->uterine_involution,
            'vitamin_a' => $this->vitamin_a,
            'fe_tablets' => $this->fe_tablets ?: 0,
            'complication_check' => $this->complication_check,
            'conclusion' => $this->conclusion,
        ];

        if ($this->isEditMode) {
            $visit = PostnatalVisit::findOrFail($this->postnatal_visit_id);
            $visit->update($data);
        } else {
            PostnatalVisit::create($data);
        }

        // Reset form and edit state
        $this->isEditMode = false;
        $this->postnatal_visit_id = null;
        $this->resetForm();

        // Refresh visits list and show success toast
        $this->loadVisits();
        $this->showSuccess = true;
        $this->showSuccessModal = true;

        // Use Livewire dispatch (available in this Livewire version) so frontend can switch to history tab
        $this->dispatch('postnatal-visit-saved');
    }

    public function cancelEdit()
    {
        $this->isEditMode = false;
        $this->postnatal_visit_id = null;
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->visit_date = now()->format('Y-m-d');
        $this->visit_code = '';
        $this->td_systolic = '';
        $this->td_diastolic = '';
        $this->temperature = '';
        $this->lochea = '';
        $this->uterine_involution = '';
        $this->vitamin_a = false;
        $this->fe_tablets = 0;
        $this->complication_check = false;
        $this->conclusion = '';
        $this->visit_date_warning = '';
    }

    public function deleteVisit($visitId)
    {
        $visit = PostnatalVisit::findOrFail($visitId);

        // Pastikan visit milik pregnancy yang benar
        if ($visit->pregnancy_id !== $this->pregnancy->id) {
            session()->flash('error', 'Tidak dapat menghapus kunjungan ini.');
            return;
        }

        $visit->delete();

        // Reset form jika sedang edit visit yang dihapus
        if ($this->isEditMode && $this->postnatal_visit_id == $visitId) {
            $this->cancelEdit();
        }

        // Refresh visits data
        $this->loadVisits();

        $this->dispatch('visit-deleted');
    }

    private function loadVisits()
    {
        // Force refresh of visits data
        $this->existingVisits = $this->pregnancy->postnatalVisits()->orderBy('visit_date')->get();
    }

    public function render()
    {
        if (!isset($this->existingVisits)) {
            $this->loadVisits();
        }

        return view('livewire.postnatal-entry', [
            'existingVisits' => $this->existingVisits,
        ]);
    }
}