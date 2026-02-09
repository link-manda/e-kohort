<?php

namespace App\Livewire;

use App\Exports\MonthlyGrowthExport;
use App\Exports\IndividualGrowthExport;
use App\Models\Child;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class ExportGrowth extends Component
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
            'year' => 'required|integer|min:2020|max:' . (date('Y') + 1),
        ]);

        $monthName = Carbon::create($this->year, $this->month)->locale('id')->translatedFormat('F');
        $fileName = "Laporan_Gizi_Bulanan_{$monthName}_{$this->year}.xlsx";

        session()->flash('success', 'Export laporan bulanan sedang diproses...');

        return Excel::download(new MonthlyGrowthExport($this->month, $this->year), $fileName);
    }

    public function exportIndividual()
    {
        $this->validate([
            'child_id' => 'required|exists:children,id',
        ]);

        $child = Child::find($this->child_id);
        $childName = $child ? str_replace(' ', '_', $child->name) : 'Anak';
        $fileName = "Riwayat_Pertumbuhan_{$childName}.xlsx";

        session()->flash('success', 'Export riwayat individual sedang diproses...');

        return Excel::download(new IndividualGrowthExport($this->child_id), $fileName);
    }

    public function render()
    {
        // Get children for dropdown
        $children = Child::orderBy('name')->get();
        return view('livewire.export-growth', compact('children'))->layout('layouts.dashboard');
    }
}
