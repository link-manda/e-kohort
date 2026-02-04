<?php

namespace App\Livewire;

use App\Models\GeneralVisit;
use App\Models\Patient;
use App\Models\Prescription;
use Livewire\Component;

class GeneralVisitEntry extends Component
{
    public $patient;
    public $visit_date;

    // Tab 1: Anamnesa (Subjective)
    public $complaint;
    public $allergies;
    public $medical_history;
    public $consciousness = 'Compos Mentis';
    public $is_emergency = false;
    public $lifestyle_smoking = 'Tidak';
    public $lifestyle_alcohol = false;
    public $lifestyle_activity = 'Aktif';
    public $lifestyle_diet = 'Sehat';

    // Tab 2: Objektif (Objective)
    public $systolic;
    public $diastolic;
    public $temperature;
    public $respiratory_rate;
    public $heart_rate;
    public $weight;
    public $height;
    public $waist_circumference;
    public $bmi; // Auto calculated
    public $physical_exam;

    // Head-to-Toe Assessment (JSON)
    public $head_to_toe = [
        'kepala' => 'Normal',
        'mata' => 'Konjungtiva anemis (-/-), Sklera ikterik (-/-)',
        'telinga' => 'Normal',
        'leher' => 'Tidak ada pembesaran kelenjar',
        'thorax_jantung' => 'BJ I-II regular, murmur (-)',
        'thorax_paru' => 'Vesikuler, ronkhi (-/-), wheezing (-/-)',
        'abdomen' => 'Supel, bising usus (+) normal',
        'ekstremitas' => 'Akral hangat, edema (-)',
        'genitalia' => 'Tidak diperiksa',
    ];

    // Tab 3: Assessment & Plan
    public $diagnosis;
    public $icd10_code;
    public $therapy;
    public $status = 'Pulang';
    public $payment_method = 'Umum';

    // Prescriptions (Resep Obat) - Array of items
    public $prescriptions = [];

    // Search ICD-10
    public $icd_search = '';
    public $icd_results = [];
    public $show_icd_dropdown = false;

    protected $rules = [
        'visit_date' => 'required|date',
        'complaint' => 'required|string',
        'allergies' => 'nullable|string',
        'medical_history' => 'nullable|string',
        'consciousness' => 'required|in:Compos Mentis,Somnolen,Sopor,Koma',
        'is_emergency' => 'boolean',
        'lifestyle_smoking' => 'required|in:Tidak,Ya,Jarang',
        'lifestyle_alcohol' => 'boolean',
        'lifestyle_activity' => 'required|in:Aktif,Kurang Olahraga',
        'lifestyle_diet' => 'required|in:Sehat,Kurang Sayur/Buah,Tinggi Gula/Garam/Lemak',
        'systolic' => 'nullable|integer|min:0|max:300',
        'diastolic' => 'nullable|integer|min:0|max:200',
        'temperature' => 'nullable|numeric|min:30|max:45',
        'respiratory_rate' => 'nullable|integer|min:0|max:100',
        'heart_rate' => 'nullable|integer|min:0|max:300',
        'weight' => 'nullable|numeric|min:0|max:500',
        'height' => 'nullable|numeric|min:0|max:300',
        'waist_circumference' => 'nullable|numeric|min:0|max:300',
        'physical_exam' => 'nullable|string',
        'diagnosis' => 'required|string',
        'icd10_code' => 'nullable|string',
        'therapy' => 'nullable|string',
        'status' => 'required|in:Pulang,Rujuk,Rawat Inap',
        'payment_method' => 'required|in:Umum,BPJS',
        // Prescriptions will be validated manually in save() method
    ];

    public function mount($patient_id)
    {
        $this->patient = Patient::findOrFail($patient_id);
        $this->visit_date = now()->format('Y-m-d\TH:i');

        // Initialize with 1 empty prescription row
        $this->prescriptions = [[
            'medicine_name' => '',
            'quantity' => '',
            'quantity_number' => 1,
            'unit_price' => 0,
            'signa' => '',
            'dosage' => '',
            'frequency' => '',
            'duration' => '',
            'notes' => '',
        ]];
    }

    // Auto calculate BMI when weight or height changes
    public function updatedWeight()
    {
        $this->calculateBMI();
    }

    public function updatedHeight()
    {
        $this->calculateBMI();
    }

    public function calculateBMI()
    {
        if ($this->weight && $this->height && $this->height > 0) {
            $heightInMeters = $this->height / 100;
            $this->bmi = round($this->weight / ($heightInMeters * $heightInMeters), 2);
        } else {
            $this->bmi = null;
        }
    }

    public function getBMICategory()
    {
        if (!$this->bmi) return '';

        if ($this->bmi < 18.5) return 'Kurus';
        if ($this->bmi < 25) return 'Normal';
        if ($this->bmi < 30) return 'Gemuk';
        return 'Obesitas';
    }

    // Prescription Management
    public function addPrescription()
    {
        $this->prescriptions[] = [
            'medicine_name' => '',
            'quantity' => '',
            'quantity_number' => 1,
            'unit_price' => 0,
            'signa' => '',
            'dosage' => '',
            'frequency' => '',
            'duration' => '',
            'notes' => '',
        ];
    }

    public function removePrescription($index)
    {
        unset($this->prescriptions[$index]);
        $this->prescriptions = array_values($this->prescriptions); // Reindex
    }

    public function calculatePrescriptionTotal($index)
    {
        if (isset($this->prescriptions[$index])) {
            $qty = $this->prescriptions[$index]['quantity_number'] ?? 0;
            $price = $this->prescriptions[$index]['unit_price'] ?? 0;
            return $qty * $price;
        }
        return 0;
    }

    public function getTotalPrescriptionCost()
    {
        $total = 0;
        foreach ($this->prescriptions as $index => $prescription) {
            $total += $this->calculatePrescriptionTotal($index);
        }
        return $total;
    }

    public function updatedIcdSearch($value)
    {
        if (strlen($value) >= 2) {
            $this->icd_results = \App\Models\Icd10Code::search($value)->take(10)->toArray();
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
        // Validate main form fields
        $this->validate();

        // Validate prescriptions manually (only if medicine_name is filled)
        $filledPrescriptions = array_filter($this->prescriptions, function ($rx) {
            return !empty($rx['medicine_name']);
        });

        // Custom validation for filled prescriptions
        foreach ($filledPrescriptions as $index => $rx) {
            $this->validate([
                "prescriptions.$index.medicine_name" => 'required|string',
                "prescriptions.$index.quantity" => 'required|string',
                "prescriptions.$index.quantity_number" => 'required|integer|min:1',
                "prescriptions.$index.unit_price" => 'required|numeric|min:0',
                "prescriptions.$index.signa" => 'required|string',
            ], [
                "prescriptions.$index.medicine_name.required" => "Nama obat harus diisi",
                "prescriptions.$index.quantity.required" => "Jumlah obat harus diisi",
                "prescriptions.$index.quantity_number.required" => "Qty angka harus diisi",
                "prescriptions.$index.unit_price.required" => "Harga obat harus diisi",
                "prescriptions.$index.signa.required" => "Aturan pakai harus diisi",
            ]);
        }

        // Create General Visit
        $visit = GeneralVisit::create([
            'patient_id' => $this->patient->id,
            'visit_date' => $this->visit_date,

            // Subjective
            'complaint' => $this->complaint,
            'allergies' => $this->allergies,
            'medical_history' => $this->medical_history,
            'consciousness' => $this->consciousness,
            'is_emergency' => $this->is_emergency,

            // Lifestyle
            'lifestyle_smoking' => $this->lifestyle_smoking,
            'lifestyle_alcohol' => $this->lifestyle_alcohol,
            'lifestyle_activity' => $this->lifestyle_activity,
            'lifestyle_diet' => $this->lifestyle_diet,

            // Objective - Vital Signs
            'systolic' => $this->systolic ?: null,
            'diastolic' => $this->diastolic ?: null,
            'temperature' => $this->temperature ?: null,
            'respiratory_rate' => $this->respiratory_rate ?: null,
            'heart_rate' => $this->heart_rate ?: null,
            'weight' => $this->weight ?: null,
            'height' => $this->height ?: null,
            'waist_circumference' => $this->waist_circumference ?: null,
            'bmi' => $this->bmi,

            // Objective - Physical
            'physical_exam' => $this->physical_exam,
            'physical_assessment_details' => $this->head_to_toe,

            // Assessment
            'diagnosis' => $this->diagnosis,
            'icd10_code' => $this->icd10_code,

            // Plan
            'therapy' => $this->therapy,
            'status' => $this->status,
            'payment_method' => $this->payment_method,
        ]);

        // Save Prescriptions (Resep Obat) - only if medicine_name is filled
        $savedPrescriptions = 0;
        foreach ($this->prescriptions as $rx) {
            if (!empty($rx['medicine_name'])) {
                Prescription::create([
                    'general_visit_id' => $visit->id,
                    'medicine_name' => $rx['medicine_name'],
                    'quantity' => $rx['quantity'],
                    'quantity_number' => $rx['quantity_number'],
                    'unit_price' => $rx['unit_price'],
                    'signa' => $rx['signa'],
                    'dosage' => $rx['dosage'] ?? null,
                    'frequency' => $rx['frequency'] ?? null,
                    'duration' => $rx['duration'] ?? null,
                    'notes' => $rx['notes'] ?? null,
                    // total_price will be auto-calculated by model
                ]);
                $savedPrescriptions++;
            }
        }

        if ($savedPrescriptions > 0) {
            session()->flash('success', 'Kunjungan Umum berhasil disimpan dengan ' . $savedPrescriptions . ' resep obat.');
        } else {
            session()->flash('success', 'Kunjungan Umum berhasil disimpan (tanpa resep obat).');
        }

        return redirect()->route('patients.show', $this->patient->id);
    }

    public function render()
    {
        return view('livewire.general-visit-entry')->layout('layouts.dashboard');
    }
}
