<?php

namespace App\Livewire;

use App\Models\Patient;
use Livewire\Component;
use Illuminate\Validation\Rule;

class PatientEdit extends Component
{
    public Patient $patient;

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
    public $showDeleteConfirm = false;

    public function mount(Patient $patient)
    {
        $this->patient = $patient;

        // Pre-fill form with existing data
        $this->nik = $patient->nik;
        $this->no_kk = $patient->no_kk;
        $this->no_bpjs = $patient->no_bpjs;
        $this->name = $patient->name;
        $this->dob = $patient->dob->format('Y-m-d');
        $this->blood_type = $patient->blood_type;
        $this->phone = $patient->phone;
        $this->address = $patient->address;
        $this->husband_name = $patient->husband_name;
        $this->husband_nik = $patient->husband_nik;
        $this->husband_job = $patient->husband_job;
    }

    protected function rules()
    {
        $rules = [
            // NIK cannot be changed, but still validated
            'nik' => [
                'required',
                'digits:16',
                Rule::unique('patients', 'nik')->ignore($this->patient->id),
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

        // Update patient
        $this->patient->update([
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

        $this->showSuccess = true;

        // Flash success message
        session()->flash('success', "Data pasien {$this->patient->name} berhasil diperbarui!");

        // Redirect after 2 seconds
        $this->dispatch('patient-updated', patientId: $this->patient->id);
    }

    public function confirmDelete()
    {
        $this->showDeleteConfirm = true;
    }

    public function cancelDelete()
    {
        $this->showDeleteConfirm = false;
    }

    public function deletePatient()
    {
        // Soft delete patient
        $patientName = $this->patient->name;
        $this->patient->delete();

        // Flash success message
        session()->flash('success', "Pasien {$patientName} berhasil dihapus.");

        // Redirect to patient list
        return redirect()->route('patients.index');
    }

    public function render()
    {
        return view('livewire.patient-edit', [
            'bloodTypes' => ['A', 'B', 'AB', 'O'],
            'progressPercentage' => ($this->currentStep / $this->totalSteps) * 100,
        ])->layout('layouts.dashboard');
    }
}