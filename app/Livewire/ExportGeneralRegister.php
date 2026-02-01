<?php

namespace App\Livewire;

use App\Exports\GeneralRegisterExport;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

class ExportGeneralRegister extends Component
{
    public $dateFrom;
    public $dateTo;

    public function mount()
    {
        $this->dateFrom = now()->startOfMonth()->format('Y-m-d');
        $this->dateTo = now()->endOfMonth()->format('Y-m-d');
    }

    public function export()
    {
        $this->validate([
            'dateFrom' => 'required|date',
            'dateTo' => 'required|date|after_or_equal:dateFrom',
        ]);

        $filename = 'Register_Poli_Umum_' . now()->format('Ymd_His') . '.xlsx';

        return Excel::download(new GeneralRegisterExport($this->dateFrom, $this->dateTo), $filename);
    }

    public function render()
    {
        return view('livewire.export-general-register')->layout('layouts.dashboard');
    }
}
