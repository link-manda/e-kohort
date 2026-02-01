<?php

namespace App\Livewire;

use App\Models\Patient;
use Livewire\Component;

class PatientQueueEntry extends Component
{
    public $search = '';
    public $results = [];
    public $selectedPatient = null;
    public $selectedPatientId = null;
    public $showNifasWarning = false;

    public function updatedSearch($value)
    {
        $value = trim($value);

        if (strlen($value) >= 2) {
            $this->results = Patient::query()
                ->where('name', 'like', '%' . $value . '%')
                ->orWhere('nik', 'like', '%' . $value . '%')
                ->orWhere('no_rm', 'like', '%' . $value . '%')
                ->limit(10)
                ->get();
        } else {
            $this->results = [];
        }
    }

    public function selectPatient($patientId)
    {
        $this->selectedPatient = Patient::find($patientId);
        $this->selectedPatientId = $patientId;
        $this->search = '';
        $this->results = [];
    }

    public function resetSelection()
    {
        $this->selectedPatient = null;
        $this->selectedPatientId = null;
        $this->showNifasWarning = false;
    }

    public function selectService($service)
    {
        if (!$this->selectedPatient) {
            return;
        }

        switch ($service) {
            case 'general':
                return redirect()->route('general-visits.create', ['patient_id' => $this->selectedPatientId]);
            case 'kia':
                $activePregnancy = $this->selectedPatient->activePregnancy;
                if ($activePregnancy) {
                    return redirect()->route('anc-visits.create', ['pregnancy' => $activePregnancy->id]);
                } else {
                    return redirect()->route('pregnancies.create', ['patient' => $this->selectedPatientId]);
                }
            case 'kb':
                return redirect()->route('kb.entry', ['patient_id' => $this->selectedPatientId]);
            case 'child':
                // Redirect to Immunization Index with search pre-filled
                return redirect()->route('imunisasi.index', ['search' => $this->selectedPatient->name]);
            case 'nifas':
                // Check if patient has any delivered pregnancy
                $deliveredPregnancy = $this->selectedPatient->pregnancies()->where('status', 'Lahir')->latest()->first();

                if (!$deliveredPregnancy) {
                    $this->showNifasWarning = true;
                    return;
                }
                return redirect()->route('pregnancies.postnatal', ['pregnancy' => $deliveredPregnancy->id]);
        }
    }

    public function proceedToNifas()
    {
        // Create a placeholder/historical pregnancy record to allow Nifas entry
        // We use dummy dates since we don't know them, but they are required by schema
        $pregnancy = \App\Models\Pregnancy::create([
            'patient_id' => $this->selectedPatientId,
            'status' => 'Lahir',
            'gravida' => 'G1P0A0', // Placeholder
            'hpht' => now()->subMonths(9), // Approximate
            'hpl' => now(), // Approximate
            'delivery_date' => now(), // Assume recent
        ]);

        return redirect()->route('pregnancies.postnatal', ['pregnancy' => $pregnancy->id]);
    }

    public function render()
    {
        return view('livewire.patient-queue-entry')->layout('layouts.dashboard');
    }
}
