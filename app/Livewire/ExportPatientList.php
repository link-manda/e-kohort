<?php

namespace App\Livewire;

use App\Exports\PatientExport;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

class ExportPatientList extends Component
{
    public $dateFrom;
    public $dateTo;
    public $pregnancyStatus = 'all';
    public $hasActivePregnancy = 'all';

    public function mount()
    {
        // Default date range: current year
        $this->dateFrom = now()->startOfYear()->format('Y-m-d');
        $this->dateTo = now()->endOfYear()->format('Y-m-d');
    }

    public function export()
    {
        $this->validate([
            'dateFrom' => 'required|date',
            'dateTo' => 'required|date|after_or_equal:dateFrom',
        ], [
            'dateFrom.required' => 'Tanggal mulai harus diisi',
            'dateTo.required' => 'Tanggal akhir harus diisi',
            'dateTo.after_or_equal' => 'Tanggal akhir harus setelah atau sama dengan tanggal mulai',
        ]);

        $filters = [
            'date_from' => $this->dateFrom,
            'date_to' => $this->dateTo,
            'pregnancy_status' => $this->pregnancyStatus !== 'all' ? $this->pregnancyStatus : null,
            'has_active_pregnancy' => $this->hasActivePregnancy !== 'all' ? $this->hasActivePregnancy : null,
        ];

        $filename = 'Data_Pasien_' . now()->format('Y-m-d_His') . '.xlsx';

        session()->flash('success', 'Export berhasil! File sedang diunduh...');

        return Excel::download(new PatientExport($filters), $filename);
    }

    public function exportCsv()
    {
        $this->validate([
            'dateFrom' => 'required|date',
            'dateTo' => 'required|date|after_or_equal:dateFrom',
        ]);

        $filters = [
            'date_from' => $this->dateFrom,
            'date_to' => $this->dateTo,
            'pregnancy_status' => $this->pregnancyStatus !== 'all' ? $this->pregnancyStatus : null,
            'has_active_pregnancy' => $this->hasActivePregnancy !== 'all' ? $this->hasActivePregnancy : null,
        ];

        $filename = 'Data_Pasien_' . now()->format('Y-m-d_His') . '.csv';

        session()->flash('success', 'Export CSV berhasil! File sedang diunduh...');

        return Excel::download(new PatientExport($filters), $filename, \Maatwebsite\Excel\Excel::CSV);
    }

    public function render()
    {
        return view('livewire.export-patient-list')->layout('layouts.dashboard');
    }
}
