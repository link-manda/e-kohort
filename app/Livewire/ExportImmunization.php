<?php

namespace App\Livewire;

use App\Exports\MonthlyImmunizationExport;
use App\Exports\IndividualImmunizationExport;
use App\Models\Child;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

class ExportImmunization extends Component
{
    public $month;
    public $year;

    public $child_id;

    public function mount()
    {
        $this->month = now()->format('n');
        $this->year = now()->format('Y');
    }

    public function exportMonthly()
    {
        $this->validate([
            'month' => 'required|integer|between:1,12',
            'year' => 'required|integer|min:2000|max:' . (date('Y') + 1),
        ]);

        $monthName = \Carbon\Carbon::create($this->year, $this->month)->locale('id')->translatedFormat('F');
        $fileName = "Laporan_Imunisasi_{$monthName}_{$this->year}.xlsx";

        session()->flash('success', 'Export bulanan sedang diproses. File akan diunduh.');

        return Excel::download(new MonthlyImmunizationExport($this->month, $this->year), $fileName);
    }

    public function exportIndividual()
    {
        $this->validate([
            'child_id' => 'required|exists:children,id',
        ]);

        $child = Child::find($this->child_id);
        $childName = $child ? str_replace(' ', '_', $child->name) : 'child';
        $fileName = "Riwayat_Imunisasi_{$childName}.xlsx";

        session()->flash('success', 'Export riwayat individual sedang diproses. File akan diunduh.');

        return Excel::download(new IndividualImmunizationExport($this->child_id), $fileName);
    }

    public function render()
    {
        $children = Child::with('patient')->orderBy('name')->get();
        return view('livewire.export-immunization', compact('children'))->layout('layouts.dashboard');
    }
}