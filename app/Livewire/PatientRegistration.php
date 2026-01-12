<?php

namespace App\Livewire;

use App\Models\Patient;
use Livewire\Component;
use Illuminate\Validation\Rule;

class PatientRegistration extends Component
{
    // Wizard state
    public $currentStep = 1;
    public $totalSteps = 2;

    // Step 1: Patient Personal Data
    public $nik = '';
    public $no_rm = '';
    public $no_kk = '';
    public $no_bpjs = '';
    public $name = '';
    public $dob = '';
    public $pob = '';
    public $job = '';
    public $education = '';
    public $blood_type = '';
    public $phone = '';
    public $address = '';

    // Step 2: Husband Data (Optional)
    public $husband_name = '';
    public $husband_nik = '';
    public $husband_job = '';
    public $husband_education = '';
    public $husband_blood_type = '';

    // UI state
    public $showSuccess = false;
    public $registeredPatientId = null;

    protected function rules()
    {
        $rules = [
            'nik' => [
                'nullable',
                'digits:16',
                Rule::unique('patients', 'nik'),
            ],
            'no_rm' => 'nullable|string|max:20',
            'name' => 'required|string|max:255',
            'dob' => 'required|date|before:today',
            'pob' => 'nullable|string|max:100',
            'job' => 'nullable|string|max:100',
            'education' => 'nullable|string|max:100',
            'blood_type' => 'required|in:A,B,AB,O',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'no_kk' => 'nullable|string|max:16',
            'no_bpjs' => 'nullable|string|max:13|regex:/^[0-9]*$/',

            // Step 2 - Optional but validated if provided
            'husband_name' => 'nullable|string|max:255',
            'husband_nik' => 'nullable|digits:16',
            'husband_job' => 'nullable|string|max:100',
            'husband_education' => 'nullable|string|max:100',
            'husband_blood_type' => 'nullable|in:A,B,AB,O',
        ];

        return $rules;
    }

    protected $messages = [
        'nik.digits' => 'NIK harus 16 digit',
        'nik.unique' => 'NIK sudah terdaftar di sistem',
        'name.required' => 'Nama lengkap wajib diisi',
        'dob.required' => 'Tanggal lahir wajib diisi',
        'dob.before' => 'Tanggal lahir harus sebelum hari ini',
        'blood_type.required' => 'Golongan darah wajib dipilih',
        'phone.required' => 'Nomor WhatsApp wajib diisi (menggantikan NIK untuk identifikasi)',
        'address.required' => 'Alamat wajib diisi',
        'husband_nik.digits' => 'NIK suami harus 16 digit',
        'no_bpjs.max' => 'No. BPJS maksimal 13 digit',
        'no_bpjs.regex' => 'No. BPJS hanya boleh berisi angka',
    ];

    public function mount()
    {
        $this->currentStep = 1;
    }

    public function updatedPhone($value)
    {
        // Auto-format phone number to Indonesian format
        $phone = preg_replace('/[^0-9]/', '', $value);

        // Convert 08xx to 628xx
        if (substr($phone, 0, 2) === '08') {
            $phone = '62' . substr($phone, 1);
        }

        $this->phone = $phone;
    }

    public function updatedNoBpjs($value)
    {
        // Remove non-numeric characters
        $this->no_bpjs = preg_replace('/[^0-9]/', '', $value);
    }

    public function updatedNik($value)
    {
        // Validate NIK in real-time
        $this->validateOnly('nik');
    }

    public function nextStep()
    {
        // Validate current step before proceeding
        if ($this->currentStep === 1) {
            $this->validate([
                'nik' => $this->rules()['nik'],
                'name' => $this->rules()['name'],
                'dob' => $this->rules()['dob'],
                'blood_type' => $this->rules()['blood_type'],
                'phone' => $this->rules()['phone'],
                'address' => $this->rules()['address'],
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

    public function submit()
    {
        // Final validation
        $validatedData = $this->validate();

        // Auto-generate no_rm if empty
        if (empty($this->no_rm)) {
            $this->no_rm = $this->generateNoRM();
        }

        // Convert empty strings to NULL for optional fields
        $data = [
            'nik' => $this->nik ?: null, // Convert empty string to NULL for unique constraint
            'no_rm' => $this->no_rm,
            'no_kk' => $this->no_kk ?: null,
            'no_bpjs' => $this->no_bpjs ?: null,
            'name' => $this->name,
            'dob' => $this->dob,
            'pob' => $this->pob ?: null,
            'job' => $this->job ?: null,
            'education' => $this->education ?: null,
            'blood_type' => $this->blood_type,
            'phone' => $this->phone,
            'address' => $this->address,
            'husband_name' => $this->husband_name ?: null,
            'husband_nik' => $this->husband_nik ?: null,
            'husband_job' => $this->husband_job ?: null,
            'husband_education' => $this->husband_education ?: null,
            'husband_blood_type' => $this->husband_blood_type ?: null,
        ];

        // Create patient
        $patient = Patient::create($data);

        $this->registeredPatientId = $patient->id;
        $this->showSuccess = true;

        // Flash success message
        session()->flash('success', "Pasien {$patient->name} berhasil didaftarkan dengan No. RM: {$patient->no_rm}");

        // Redirect after 2 seconds to patient detail
        $this->dispatch('patient-registered', patientId: $patient->id);
    }

    private function generateNoRM(): string
    {
        // Format: RM-YYYY-NNNN (e.g., RM-2026-0001)
        $year = date('Y');
        $prefix = "RM-{$year}-";

        // Get last patient number for this year
        $lastPatient = Patient::where('no_rm', 'like', "{$prefix}%")
            ->orderBy('no_rm', 'desc')
            ->first();

        if ($lastPatient) {
            // Extract number from last RM (e.g., RM-2026-0001 -> 0001)
            $lastNumber = (int) substr($lastPatient->no_rm, -4);
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }

        // Format with 4 digits padding
        return $prefix . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }

    public function resetForm()
    {
        $this->reset();
        $this->currentStep = 1;
    }

    public function render()
    {
        return view('livewire.patient-registration', [
            'bloodTypes' => ['A', 'B', 'AB', 'O'],
            'progressPercentage' => ($this->currentStep / $this->totalSteps) * 100,
        ])->layout('layouts.dashboard');
    }
}
