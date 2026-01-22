<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\Patient;

class PatientPreview extends Component
{
    public $open = false;
    public $patientId;
    public $patient;

    #[On('openPatientPreview')]
    public function open($id)
    {
        $this->patientId = $id;
        $this->patient = Patient::with(['pregnancies','kbVisits'=>function($q){ $q->latest()->limit(5); }])->find($id);
        $this->open = true;
    }

    public function close()
    {
        $this->open = false;
    }

    public function render()
    {
        return view('livewire.patient-preview');
    }
}
