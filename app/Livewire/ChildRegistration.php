<?php

namespace App\Livewire;

use App\Models\Patient;
use App\Models\Child;
use Livewire\Component;
use Illuminate\Validation\Rule;

class ChildRegistration extends Component
{
    // Toggle: internal (mother registered) or external (mother not registered)
    public $birth_location = 'external'; // Default to external for easier flow

    // For internal birth - search mother
    public $searchTerm = '';
    public $searchResults = [];
    public $selectedMother = null;
    public $patient_id = null;

    // For external birth - manual parent info
    public $parent_name = '';
    public $parent_phone = '';
    public $parent_address = '';

    // Child data
    public $nik = '';
    public $name = '';
    public $gender = '';
    public $dob = '';
    public $pob = '';
    public $birth_weight = '';
    public $birth_height = '';

    // UI state
    public $showSuccess = false;
    public $savedChildId = null;

    protected function rules()
    {
        $rules = [
            'birth_location' => 'required|in:internal,external',
            'nik' => [
                'nullable',
                'digits:16',
                Rule::unique('children', 'nik'),
            ],
            'name' => 'required|string|max:255',
            'gender' => 'required|in:L,P',
            'dob' => 'required|date|before_or_equal:today',
            'pob' => 'nullable|string|max:255',
            'birth_weight' => 'nullable|numeric|min:500|max:10000',
            'birth_height' => 'nullable|numeric|min:20|max:100',
        ];

        // Conditional validation based on birth_location
        if ($this->birth_location === 'internal') {
            $rules['patient_id'] = 'required|exists:patients,id';
        } else {
            // External birth - parent info required
            $rules['parent_name'] = 'required|string|max:255';
            $rules['parent_phone'] = 'required|string|max:20';
            $rules['parent_address'] = 'nullable|string|max:500';
        }

        return $rules;
    }

    protected $messages = [
        'patient_id.required' => 'Ibu harus dipilih terlebih dahulu',
        'patient_id.exists' => 'Data ibu tidak ditemukan',
        'parent_name.required' => 'Nama orang tua wajib diisi',
        'parent_phone.required' => 'No HP orang tua wajib diisi',
        'nik.digits' => 'NIK harus 16 digit',
        'nik.unique' => 'NIK sudah terdaftar',
        'name.required' => 'Nama anak wajib diisi',
        'gender.required' => 'Jenis kelamin wajib dipilih',
        'dob.required' => 'Tanggal lahir wajib diisi',
        'dob.before_or_equal' => 'Tanggal lahir tidak boleh di masa depan',
        'birth_weight.min' => 'Berat badan lahir minimal 500 gram',
        'birth_weight.max' => 'Berat badan lahir maksimal 10000 gram',
        'birth_height.min' => 'Tinggi badan lahir minimal 20 cm',
        'birth_height.max' => 'Tinggi badan lahir maksimal 100 cm',
    ];

    public function mount()
    {
        // Check if patient_id passed from registration desk
        if (request()->query('patient_id')) {
            $patientId = request()->query('patient_id');
            $patient = Patient::find($patientId);
            if ($patient) {
                $this->birth_location = 'internal';
                $this->patient_id = $patientId;
                $this->selectedMother = $patient;
            }
        }
    }

    public function updatedBirthLocation($value)
    {
        // Reset form when switching between internal/external
        if ($value === 'external') {
            $this->patient_id = null;
            $this->selectedMother = null;
            $this->searchTerm = '';
            $this->searchResults = [];
        } else {
            $this->parent_name = '';
            $this->parent_phone = '';
            $this->parent_address = '';
        }
    }

    public function updatedSearchTerm()
    {
        if (strlen($this->searchTerm) >= 3) {
            $this->searchResults = Patient::where(function($query) {
                $query->where('name', 'like', '%' . $this->searchTerm . '%')
                      ->orWhere('phone', 'like', '%' . $this->searchTerm . '%')
                      ->orWhere('nik', 'like', '%' . $this->searchTerm . '%');
            })
            ->limit(10)
            ->get();
        } else {
            $this->searchResults = [];
        }
    }

    public function selectMother($patientId)
    {
        $this->selectedMother = Patient::findOrFail($patientId);
        $this->patient_id = $patientId;
        $this->searchResults = [];
        $this->searchTerm = '';
    }

    public function clearMotherSelection()
    {
        $this->selectedMother = null;
        $this->patient_id = null;
    }

    public function submit()
    {
        $validatedData = $this->validate();

        // Build child data based on birth location
        $childData = [
            'birth_location' => $this->birth_location,
            'nik' => $this->nik ?: null,
            'name' => $this->name,
            'gender' => $this->gender,
            'dob' => $this->dob,
            'pob' => $this->pob,
            'birth_weight' => $this->birth_weight ?: null,
            'birth_height' => $this->birth_height ?: null,
            'status' => 'Hidup',
        ];

        if ($this->birth_location === 'internal') {
            $childData['patient_id'] = $this->patient_id;
            $childData['parent_name'] = null;
            $childData['parent_phone'] = null;
            $childData['parent_address'] = null;
        } else {
            $childData['patient_id'] = null;
            $childData['parent_name'] = $this->parent_name;
            $childData['parent_phone'] = $this->parent_phone;
            $childData['parent_address'] = $this->parent_address ?: null;
        }

        $child = Child::create($childData);

        session()->flash('success', "Anak {$child->name} berhasil didaftarkan dengan No. RM: {$child->no_rm}! Silakan pilih layanan.");

        // Redirect to registration desk to select service
        return redirect()->route('registration-desk');
    }

    public function render()
    {
        $nextNoRm = Child::getNextRmNumber();
        return view('livewire.child-registration', [
            'nextNoRm' => $nextNoRm,
        ]);
    }
}
