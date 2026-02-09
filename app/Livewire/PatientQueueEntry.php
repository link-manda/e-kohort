<?php

namespace App\Livewire;

use App\Models\Patient;
use App\Models\Child;
use Livewire\Component;

class PatientQueueEntry extends Component
{
    public $search = '';
    public $results = [];
    public $childResults = [];  // NEW: Results for child search
    public $selectedPatient = null;
    public $selectedPatientId = null;
    public $selectedChild = null;  // NEW: Selected child for immunization
    public $selectedChildId = null;  // NEW: Selected child ID
    public $selectionType = null;  // 'patient' or 'child'

    public function mount($patient_id = null)
    {
        // Auto-select patient if patient_id provided (from registration redirect)
        if ($patient_id) {
            $this->selectPatient($patient_id);
        }
    }

    public function updatedSearch($value)
    {
        $value = trim($value);

        if (strlen($value) >= 2) {
            // Search patients (adults/mothers)
            $this->results = Patient::query()
                ->where('name', 'like', '%' . $value . '%')
                ->orWhere('nik', 'like', '%' . $value . '%')
                ->orWhere('no_rm', 'like', '%' . $value . '%')
                ->limit(10)
                ->get();

            // Search children (for immunization)
            $this->childResults = Child::query()
                ->where('name', 'like', '%' . $value . '%')
                ->orWhere('nik', 'like', '%' . $value . '%')
                ->orWhere('no_rm', 'like', '%' . $value . '%')
                ->limit(10)
                ->get();
        } else {
            $this->results = [];
            $this->childResults = [];
        }
    }

    public function selectPatient($patientId)
    {
        $this->selectedPatient = Patient::find($patientId);
        $this->selectedPatientId = $patientId;
        $this->selectedChild = null;
        $this->selectedChildId = null;
        $this->selectionType = 'patient';
        $this->search = '';
        $this->results = [];
        $this->childResults = [];
    }

    public function selectChild($childId)
    {
        $this->selectedChild = Child::find($childId);
        $this->selectedChildId = $childId;
        $this->selectedPatient = null;
        $this->selectedPatientId = null;
        $this->selectionType = 'child';
        $this->search = '';
        $this->results = [];
        $this->childResults = [];
    }

    public function resetSelection()
    {
        $this->selectedPatient = null;
        $this->selectedPatientId = null;
        $this->selectedChild = null;
        $this->selectedChildId = null;
        $this->selectionType = null;
    }

    /**
     * Handle service selection for child
     */
    public function selectChildService($service)
    {
        if (!$this->selectedChild) {
            session()->flash('error', 'Silakan pilih anak terlebih dahulu.');
            return;
        }

        switch ($service) {
            case 'immunization':
                return redirect()->route('children.immunization', ['child' => $this->selectedChildId]);

            case 'general':
                // Poli Umum untuk anak - redirect ke halaman kunjungan umum anak
                return redirect()->route('children.general-visit', ['child_id' => $this->selectedChildId]);

            case 'growth':
                // Poli Gizi / Pertumbuhan
                return redirect()->route('children.growth', ['childId' => $this->selectedChildId]);

            default:
                session()->flash('error', 'Layanan tidak valid untuk anak.');
                return;
        }
    }

    public function selectService($service)
    {
        if (!$this->selectedPatient) {
            session()->flash('error', 'Silakan pilih pasien terlebih dahulu.');
            return;
        }

        // Auto-update category based on selected service (Phase 1 Logic)
        $this->updatePatientCategory($service);

        switch ($service) {
            case 'general':
                // Poli Umum - always available
                return redirect()->route('general-visits.create', ['patient_id' => $this->selectedPatientId]);

            case 'kia':
                // Poli KIA (ANC) - for pregnant women
                $activePregnancy = $this->selectedPatient->pregnancies()
                    ->where('status', 'Aktif')
                    ->latest()
                    ->first();

                if ($activePregnancy) {
                    // Already has active pregnancy, go to ANC visit entry
                    return redirect()->route('anc-visits.create', ['pregnancy' => $activePregnancy->id]);
                } else {
                    // No active pregnancy, create new pregnancy record first
                    return redirect()->route('pregnancies.create', ['patient' => $this->selectedPatientId]);
                }

            case 'kb':
                // KB - always available (can be used by any woman)
                return redirect()->route('kb.entry', ['patient_id' => $this->selectedPatientId]);

            case 'child':
                // Imunisasi Anak - check if there's a child record
                $child = \App\Models\Child::where('patient_id', $this->selectedPatientId)->first();

                if ($child) {
                    // Already has child record, go directly to immunization
                    return redirect()->route('children.immunization', ['child' => $child->id]);
                } else {
                    // Need to create child record first
                    return redirect()->route('children.register', ['patient_id' => $this->selectedPatientId]);
                }

            case 'nifas':
                // Poli Nifas - find pregnancy with status Lahir (regardless of delivery_date)
                // Let PostnatalEntry component handle external birth modal if delivery_date is NULL
                $deliveredPregnancy = $this->selectedPatient->pregnancies()
                    ->where('status', 'Lahir')
                    ->latest('delivery_date')
                    ->first();

                if ($deliveredPregnancy) {
                    // Has pregnancy with status 'Lahir', proceed to postnatal visit
                    // PostnatalEntry will show external birth modal if delivery_date is NULL
                    return redirect()->route('pregnancies.postnatal', ['pregnancy' => $deliveredPregnancy->id]);
                } else {
                    // No pregnancy with status 'Lahir' at all
                    // Need to create pregnancy first or patient never gave birth
                    session()->flash('error', 'Pasien tidak memiliki riwayat kehamilan dengan status Lahir. Silakan daftarkan ke Poli KIA terlebih dahulu.');
                    return;
                }

            default:
                session()->flash('error', 'Layanan tidak valid.');
                return;
        }
    }

    /**
     * Update patient category based on selected service
     * Phase 1 Logic: Auto-categorize patients when they select a service
     */
    private function updatePatientCategory($service)
    {
        $patient = $this->selectedPatient;
        $age = $patient->age;

        // Skip if category is manually set to something other than 'Umum'
        // (to avoid overriding intentional categorization)
        if ($patient->category !== 'Umum' && $patient->category !== null) {
            return;
        }

        switch ($service) {
            case 'kia':
            case 'nifas':
                // KIA/Nifas service → categorize as Bumil
                $patient->update(['category' => 'Bumil']);
                break;

            case 'child':
                // Child immunization service → categorize as Bayi/Balita
                $patient->update(['category' => 'Bayi/Balita']);
                break;

            case 'general':
            case 'kb':
                // For general/KB, use age-based logic
                if ($age < 5) {
                    $patient->update(['category' => 'Bayi/Balita']);
                } elseif ($age >= 60) {
                    $patient->update(['category' => 'Lansia']);
                } else {
                    // Keep as 'Umum' (default)
                }
                break;
        }
    }

    /**
     * Method proceedToNifas removed - no longer needed.
     * PostnatalEntry component now handles external birth with proper modal.
     */

    public function render()
    {
        return view('livewire.patient-queue-entry')->layout('layouts.dashboard');
    }
}
