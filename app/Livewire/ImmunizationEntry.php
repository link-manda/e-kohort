<?php

namespace App\Livewire;

use App\Models\Child;
use App\Models\ChildVisit;
use App\Models\ImmunizationAction;
use App\Models\Vaccine;
use App\Models\Icd10Code;
use Livewire\Component;

class ImmunizationEntry extends Component
{
    public $child;
    public $currentStep = 1;
    public $totalSteps = 2;

    // Step 1: Visit Info & Vital Signs
    public $visit_date = '';
    public $complaint = '';
    public $weight = '';
    public $height = '';
    public $temperature = '';
    public $heart_rate = '';
    public $respiratory_rate = '';
    public $head_circumference = '';
    public $development_notes = '';

    // NEW: Status Gizi & Informed Consent
    public $nutritional_status = '';
    public $informed_consent = false;

    // NEW: Medicine/KIPI Management
    public $medicine_given = '';
    public $medicine_dosage = '';
    public $notes = '';

    // Step 2: Diagnosis & Immunization Actions
    public $icd_code = '';
    public $diagnosis_name = '';
    public $vaccines = [];

    // ICD-10 Search
    public $icd_search = '';
    public $icd_results = [];
    public $show_icd_dropdown = false;

    // Vaccine row template
    public $vaccine_rows = [[
        'vaccine_type' => '',
        'batch_number' => '',
        'body_part' => '',
        'provider_name' => '',
        'override' => false,
    ]];

    // Typeahead state per row
    public $vaccine_search = [];
    public $vaccine_search_results = [];
    public $vaccine_override_confirm = ['index' => null, 'code' => null];

    // UI State
    public $showSuccess = false;
    public $temperature_warning = '';
    public $temperature_category = '';
    public $vaccine_warnings = [];
    public $existingVisits = [];

    // Allow client-side to set current step (for scroll-to-error)
    protected $listeners = ['setCurrentStep'];

    public function setCurrentStep($step)
    {
        $this->currentStep = (int) $step;
    }

    public function mount(Child $child)
    {
        $this->child = $child;
        $this->visit_date = now()->format('Y-m-d H:i');
        $this->loadVisits();
    }

    protected function rules()
    {
        $rules = [
            'visit_date' => 'required|date',
            'complaint' => 'nullable|string',
            'weight' => 'required|numeric|min:1|max:100',
            'height' => 'required|numeric|min:20|max:200',
            'temperature' => 'required|numeric|min:35|max:42',
            'heart_rate' => 'nullable|integer|min:60|max:200',
            'respiratory_rate' => 'nullable|integer|min:10|max:80',
            'head_circumference' => 'nullable|numeric|min:20|max:100',
            'development_notes' => 'nullable|string',
            'icd_code' => 'nullable|string|max:10',
            'diagnosis_name' => 'nullable|string|max:255',
            // NEW: Required fields
            'nutritional_status' => 'required|in:Gizi Buruk,Gizi Kurang,Gizi Baik,Gizi Lebih,Obesitas',
            'informed_consent' => 'accepted', // Must be true (checked) - Hard Block
            // NEW: Medicine fields
            'medicine_given' => 'nullable|in:Parasetamol Drop,Parasetamol Sirup,Tidak Ada,Lainnya',
            'medicine_dosage' => 'nullable|string|max:100',
            'notes' => 'nullable|string|max:1000',
        ];

        // Dynamic rules for vaccine rows
        foreach ($this->vaccine_rows as $index => $row) {
            if (!empty($row['vaccine_type'])) {
                $rules["vaccine_rows.{$index}.vaccine_type"] = 'required|string';
                $rules["vaccine_rows.{$index}.batch_number"] = 'nullable|string|max:100';
                $rules["vaccine_rows.{$index}.body_part"] = 'nullable|string|max:100';
                $rules["vaccine_rows.{$index}.provider_name"] = 'required|string|max:255';
            }
        }

        return $rules;
    }

    protected $messages = [
        'visit_date.required' => 'Tanggal kunjungan wajib diisi',
        'temperature.required' => 'Suhu tubuh wajib diisi',
        'temperature.min' => 'Suhu tubuh minimal 35°C',
        'temperature.max' => 'Suhu tubuh maksimal 42°C',
        'vaccine_rows.*.vaccine_type.required' => 'Jenis vaksin wajib dipilih',
        'vaccine_rows.*.provider_name.required' => 'Nama nakes wajib diisi',
        // Numeric fields
        'weight.required' => 'Berat badan wajib diisi',
        'weight.numeric' => 'Berat badan harus berupa angka',
        'weight.min' => 'Berat badan minimal 1 kg',
        'weight.max' => 'Berat badan maksimal 100 kg',
        'height.numeric' => 'Tinggi badan harus berupa angka',
        'height.min' => 'Tinggi badan minimal 20 cm',
        'height.max' => 'Tinggi badan maksimal 200 cm',
        'head_circumference.numeric' => 'Lingkar kepala harus berupa angka',
        'head_circumference.min' => 'Lingkar kepala minimal 20 cm',
        'head_circumference.max' => 'Lingkar kepala maksimal 100 cm',
        'heart_rate.integer' => 'Nadi harus berupa angka bulat',
        'heart_rate.min' => 'Nadi minimal 60 bpm',
        'heart_rate.max' => 'Nadi maksimal 200 bpm',
        'respiratory_rate.integer' => 'Nafas harus berupa angka bulat',
        'respiratory_rate.min' => 'Nafas minimal 10 per menit',
        'respiratory_rate.max' => 'Nafas maksimal 80 per menit',
        // NEW: Validation messages
        'nutritional_status.required' => 'Status gizi wajib dipilih',
        'nutritional_status.in' => 'Status gizi tidak valid',
        'informed_consent.accepted' => '⚠️ Anda harus mencentang persetujuan tindakan medis (Informed Consent) untuk melanjutkan',
        'medicine_given.in' => 'Jenis obat tidak valid',
        'medicine_dosage.max' => 'Dosis obat maksimal 100 karakter',
        'notes.max' => 'Keterangan maksimal 1000 karakter',
    ];

    public function updated($propertyName)
    {
        // Real-time temperature validation
        if ($propertyName === 'temperature') {
            $this->validateTemperature();
        }

        // Real-time vaccine age validation
        if (str_contains($propertyName, 'vaccine_rows') && str_contains($propertyName, 'vaccine_type')) {
            $this->validateVaccineAges();

            // If user selected vaccine via fallback select, we need to ensure override flow is consistent
            $parts = explode('.', $propertyName);
            $index = $parts[1] ?? null;
            if (is_numeric($index)) {
                $selected = $this->vaccine_rows[$index]['vaccine_type'] ?? null;
                if ($selected) {
                    $vaccine = Vaccine::where('code', $selected)->first();
                    if ($vaccine) {
                        $validation = $vaccine->isAgeAppropriate($this->child->age_in_months);
                        if (!$validation['appropriate'] && empty($this->vaccine_rows[$index]['override'])) {
                            // prompt override confirmation
                            $this->vaccine_override_confirm = ['index' => (int)$index, 'code' => $selected];
                        }
                    }
                }
            }
        }

        // Vaccine typeahead search per row
        if (str_starts_with($propertyName, 'vaccine_search.')) {
            // property like vaccine_search.0
            $parts = explode('.', $propertyName);
            $index = $parts[1] ?? null;
            if (is_numeric($index)) {
                $this->searchVaccinesForRow((int)$index);
            }
        }

        // ICD-10 search
        if ($propertyName === 'icd_search') {
            $this->searchIcd10();
        }
    }

    /**
     * Search ICD-10 codes based on user input.
     * Queries from database instead of config file.
     */
    public function searchIcd10()
    {
        if (strlen($this->icd_search) < 2) {
            $this->icd_results = [];
            $this->show_icd_dropdown = false;
            return;
        }

        // Query from database using Model
        $results = Icd10Code::search($this->icd_search);

        $this->icd_results = $results->map(function ($icd) {
            return [
                'code' => $icd->code,
                'name' => $icd->name,
                'description' => $icd->description,
                'keywords' => $icd->keywords ?? [],
            ];
        })->toArray();

        $this->show_icd_dropdown = count($this->icd_results) > 0;
    }

    /**
     * Select ICD-10 code from dropdown.
     * Query from database instead of config.
     */
    public function selectIcd10($code)
    {
        $icdData = Icd10Code::where('code', $code)->first();

        if ($icdData) {
            $this->icd_code = $icdData->code;
            $this->diagnosis_name = $icdData->description;
            $this->icd_search = $icdData->code . ' - ' . $icdData->name;
            $this->show_icd_dropdown = false;
        }
    }

    /**
     * Clear ICD-10 selection.
     */
    public function clearIcd10()
    {
        $this->icd_code = '';
        $this->diagnosis_name = '';
        $this->icd_search = '';
        $this->icd_results = [];
        $this->show_icd_dropdown = false;
    }

    public function validateTemperature()
    {
        if ($this->temperature) {
            $temp = (float) $this->temperature;

            if ($temp >= 38) {
                $this->temperature_category = 'danger';
                $this->temperature_warning = '⚠️ DEMAM TINGGI! Pertimbangkan untuk menunda imunisasi';
            } elseif ($temp > 37.5) {
                $this->temperature_category = 'warning';
                $this->temperature_warning = '⚠️ Subfebris - Konsultasikan dengan dokter';
            } elseif ($temp < 36) {
                $this->temperature_category = 'warning';
                $this->temperature_warning = '⚠️ Suhu tubuh rendah (hipotermia)';
            } else {
                $this->temperature_category = 'normal';
                $this->temperature_warning = '✓ Suhu tubuh normal';
            }
        } else {
            $this->temperature_warning = '';
            $this->temperature_category = '';
        }
    }

    public function validateVaccineAges()
    {
        $this->vaccine_warnings = [];
        $ageInMonths = $this->child->age_in_months;

        foreach ($this->vaccine_rows as $index => $row) {
            if (!empty($row['vaccine_type'])) {
                // Query vaccine from database
                $vaccine = Vaccine::where('code', $row['vaccine_type'])->first();

                if ($vaccine) {
                    $validation = $vaccine->isAgeAppropriate($ageInMonths);

                    // Allow override
                    if (!empty($row['override'])) {
                        $validation = [
                            'appropriate' => true,
                            'status' => 'override',
                            'message' => "Override: memberikan {$vaccine->name} meskipun usia tidak ideal",
                        ];
                    }

                    $this->vaccine_warnings[$index] = $validation;
                }
            }
        }
    }

    public function addVaccineRow()
    {
        $this->vaccine_rows[] = [
            'vaccine_type' => '',
            'batch_number' => '',
            'body_part' => '',
            'provider_name' => '',
            'override' => false,
        ];
    }

    /**
     * Search vaccines for a specific row (typeahead)
     */
    public function searchVaccinesForRow(int $index)
    {
        $term = $this->vaccine_search[$index] ?? '';
        if (strlen($term) < 1) {
            $this->vaccine_search_results[$index] = [];
            return;
        }

        $results = Vaccine::active()
            ->where(function($q) use ($term) {
                $q->where('name', 'like', "%{$term}%")
                  ->orWhere('code', 'like', "%{$term}%");
            })
            ->orderBy('sort_order')
            ->limit(10)
            ->get();

        // Map with age appropriateness
        $mapped = $results->map(function($v) use ($index) {
            $age = $this->child->age_in_months;
            $validation = $v->isAgeAppropriate($age);
            return [
                'code' => $v->code,
                'name' => $v->name,
                'min' => $v->min_age_months,
                'max' => $v->max_age_months,
                'status' => $validation['status'],
                'message' => $validation['message'],
            ];
        })->toArray();

        $this->vaccine_search_results[$index] = $mapped;
    }

    public function selectVaccine($index, $code, $force = false)
    {
        // If not forced and vaccine is not appropriate, show confirm modal
        $vaccine = Vaccine::where('code', $code)->first();
        if (!$vaccine) return;

        $age = $this->child->age_in_months;
        $validation = $vaccine->isAgeAppropriate($age);

        if (!$validation['appropriate'] && !$force) {
            // Ask for confirmation override
            $this->vaccine_override_confirm = ['index' => $index, 'code' => $code];
            return;
        }

        // Set vaccine
        $this->vaccine_rows[$index]['vaccine_type'] = $code;
        // clear search
        $this->vaccine_search[$index] = '';
        $this->vaccine_search_results[$index] = [];

        // If force override, mark override flag
        if ($force) {
            $this->vaccine_rows[$index]['override'] = true;
        }

        $this->validateVaccineAges();
    }

    public function confirmOverrideSelection($confirm = false)
    {
        $i = $this->vaccine_override_confirm['index'];
        $code = $this->vaccine_override_confirm['code'];
        if ($confirm && is_numeric($i) && $code) {
            // Force select
            $this->selectVaccine($i, $code, true);
        }
        $this->vaccine_override_confirm = ['index' => null, 'code' => null];
    }
    public function removeVaccineRow($index)
    {
        unset($this->vaccine_rows[$index]);
        $this->vaccine_rows = array_values($this->vaccine_rows);
        $this->validateVaccineAges();
    }

    public function nextStep()
    {
        if ($this->currentStep === 1) {
            $this->validate([
                'visit_date' => $this->rules()['visit_date'],
                'temperature' => $this->rules()['temperature'],
            ]);
        }

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
        // Dispatch attempt event & log attempt
        \Illuminate\Support\Facades\Log::info('ImmunizationEntry::save attempt', ['child_id' => $this->child->id ?? null]);
        // Dispatch event for frontend listeners
        $this->dispatch('immunizationSaveAttempt');

        // Normalize numeric fields: convert empty strings to null to satisfy nullable numeric rules
        foreach (['weight','height','heart_rate','respiratory_rate','head_circumference'] as $nfield) {
            if (isset($this->$nfield) && $this->$nfield === '') {
                $this->$nfield = null;
            }
        }

        // Validate and let Livewire handle the validation errors (rethrow after dispatch)
        try {
            $this->validate();
        } catch (\Illuminate\Validation\ValidationException $ve) {
            // Log and dispatch a validation-failed event with errors
            \Illuminate\Support\Facades\Log::warning('ImmunizationEntry::validation failed', ['errors' => $ve->errors()]);
            // Dispatch validation failed so frontend can show messages
            $this->dispatch('immunizationValidationFailed', $ve->errors());
            // Rethrow so Livewire will populate the error bag for inline display
            throw $ve;
        }

        try {
            // Calculate age in months at visit
            $visitDate = \Carbon\Carbon::parse($this->visit_date);
            $ageMonth = $this->child->dob->diffInMonths($visitDate);

            // Create visit record
            $visit = ChildVisit::create([
                'child_id' => $this->child->id,
                'visit_date' => $this->visit_date,
                'age_month' => $ageMonth,
                'complaint' => $this->complaint,
                'weight' => $this->weight ?: null,
                'height' => $this->height ?: null,
                'temperature' => $this->temperature,
                'heart_rate' => $this->heart_rate ?: null,
                'respiratory_rate' => $this->respiratory_rate ?: null,
                'head_circumference' => $this->head_circumference ?: null,
                'development_notes' => $this->development_notes,
                'icd_code' => $this->icd_code,
                'diagnosis_name' => $this->diagnosis_name,
                // NEW: Additional fields
                'nutritional_status' => $this->nutritional_status,
                'informed_consent' => $this->informed_consent,
                'medicine_given' => $this->medicine_given ?: null,
                'medicine_dosage' => $this->medicine_dosage ?: null,
                'notes' => $this->notes ?: null,
            ]);

            // Create immunization actions
            foreach ($this->vaccine_rows as $row) {
                if (!empty($row['vaccine_type'])) {
                    ImmunizationAction::create([
                        'child_visit_id' => $visit->id,
                        'vaccine_type' => $row['vaccine_type'],
                        'batch_number' => $row['batch_number'],
                        'body_part' => $row['body_part'],
                        'provider_name' => $row['provider_name'],
                    ]);
                }
            }

            $this->showSuccess = true;
            $this->loadVisits();
            $this->resetForm();

            session()->flash('success', 'Kunjungan imunisasi berhasil disimpan!');

            \Illuminate\Support\Facades\Log::info('ImmunizationEntry::save success', ['visit_id' => $visit->id]);

            // Dispatch success event so frontend can show a toast and redirect
            $this->dispatch('immunizationSaved', [
                'message' => 'Kunjungan imunisasi berhasil disimpan!',
                'redirect' => route('imunisasi.index'),
                'redirectDelay' => 1800, // ms
            ]);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('ImmunizationEntry::save failed - ' . $e->getMessage(), ['exception' => $e]);
            // Dispatch failure event with message
            $this->dispatch('immunizationSaveFailed', $e->getMessage());
            // Do not rethrow to allow friendly UX; return instead
            return;
        }
    }

    private function resetForm()
    {
        $this->currentStep = 1;
        $this->visit_date = now()->format('Y-m-d H:i');
        $this->complaint = '';
        $this->weight = '';
        $this->height = '';
        $this->temperature = '';
        $this->heart_rate = '';
        $this->respiratory_rate = '';
        $this->head_circumference = '';
        $this->development_notes = '';
        $this->icd_code = '';
        $this->diagnosis_name = '';
        // NEW: Reset new fields
        $this->nutritional_status = '';
        $this->informed_consent = false;
        $this->medicine_given = '';
        $this->medicine_dosage = '';
        $this->notes = '';
        $this->vaccine_rows = [[
            'vaccine_type' => '',
            'batch_number' => '',
            'body_part' => '',
            'provider_name' => '',
        ]];
        $this->temperature_warning = '';
        $this->vaccine_warnings = [];
    }

    private function loadVisits()
    {
        $this->existingVisits = $this->child->childVisits()
            ->with('immunizationActions')
            ->orderBy('visit_date', 'desc')
            ->get();
    }

    public function render()
    {
        // Query vaccines from database instead of static method
        $available_vaccines = Vaccine::active()->ordered()->get()->mapWithKeys(function ($vaccine) {
            return [$vaccine->code => [
                'name' => $vaccine->name,
                'description' => $vaccine->description,
                'min_age' => $vaccine->min_age_months,
                'max_age' => $vaccine->max_age_months,
            ]];
        })->toArray();

        return view('livewire.immunization-entry', [
            'available_vaccines' => $available_vaccines,
        ]);
    }
}
