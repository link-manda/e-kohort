<?php

namespace App\Livewire;

use App\Exports\KbDailyReportExport;
use App\Models\KbMethod;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

class ExportKb extends Component
{
    public $start_date;
    public $end_date;
    public $visit_type = '';
    public $payment_type = '';
    public $kb_method_id = '';

    public function mount()
    {
        $this->start_date = now()->startOfMonth()->format('Y-m-d');
        $this->end_date = now()->endOfMonth()->format('Y-m-d');
    }

    public function export()
    {
        $this->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $filename = 'Laporan_KB_' . date('Y-m-d_His') . '.xlsx';

        return Excel::download(
            new KbDailyReportExport(
                $this->start_date,
                $this->end_date,
                $this->visit_type ?: null,
                $this->payment_type ?: null,
                $this->kb_method_id ?: null
            ),
            $filename
        );
    }

    public function render()
    {
        return view('livewire.export-kb', [
            'kbMethods' => KbMethod::active()->orderBy('category')->orderBy('name')->get(),
        ]);
    }
}
