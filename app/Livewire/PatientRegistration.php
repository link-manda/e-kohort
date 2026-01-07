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
    public $no_kk = '';
    public $no_bpjs = '';
    public $name = '';
    public $dob = '';
    public $blood_type = '';
    public $phone = '';
    public $address = '';

    // Step 2: Husband Data (Optional)
    public $husband_name = '';
    public $husband_nik = '';
    public $husband_job = '';

    // UI state
    public $showSuccess = false;
    public $registeredPatientId = null;

    protected function rules()
    {
        $rules = [
            'nik' => [
                'required',
                'digits:16',
                Rule::unique('patients', 'nik'),
            ],
            'name' => 'required|string|max:255',
            'dob' => 'required|date|before:today',
            'blood_type' => 'required|in:A,B,AB,O',
            'phone' => 'nullable|string|max:20',
            'address' => 'required|string',
            'no_kk' => 'nullable|string|max:16',
            'no_bpjs' => 'nullable|string|max:20',

            // Step 2 - Optional but validated if provided
            'husband_name' => 'nullable|string|max:255',
            'husband_nik' => 'nullable|digits:16',
            'husband_job' => 'nullable|string|max:100',
        ];

        return $rules;
    }

    protected $messages = [
        'nik.required' => 'NIK wajib diisi',
        'nik.digits' => 'NIK harus 16 digit',
        'nik.unique' => 'NIK sudah terdaftar di sistem',
        'name.required' => 'Nama lengkap wajib diisi',
        'dob.required' => 'Tanggal lahir wajib diisi',
        'dob.before' => 'Tanggal lahir harus sebelum hari ini',
        'blood_type.required' => 'Golongan darah wajib dipilih',
        'address.required' => 'Alamat wajib diisi',
        'husband_nik.digits' => 'NIK suami harus 16 digit',
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

        // Create patient
        $patient = Patient::create([
            'nik' => $this->nik,
            'no_kk' => $this->no_kk,
            'no_bpjs' => $this->no_bpjs,
            'name' => $this->name,
            'dob' => $this->dob,
            'blood_type' => $this->blood_type,
            'phone' => $this->phone,
            'address' => $this->address,
            'husband_name' => $this->husband_name,
            'husband_nik' => $this->husband_nik,
            'husband_job' => $this->husband_job,
        ]);

        $this->registeredPatientId = $patient->id;
        $this->showSuccess = true;

        // Flash success message
        session()->flash('success', "Pasien {$patient->name} berhasil didaftarkan!");

        // Redirect after 2 seconds to patient detail
        $this->dispatch('patient-registered', patientId: $patient->id);
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
