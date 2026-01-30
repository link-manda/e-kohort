<?php

namespace App\Livewire;

use Livewire\Component;
use App\Exports\DeliveryRegisterExport;
use Maatwebsite\Excel\Facades\Excel;

class ExportDeliveryRegister extends Component
{
    public $month;
    public $year;

    protected $rules = [
        'month' => 'required|integer|min:1|max:12',
        'year' => 'required|integer|min:2020|max:2030',
    ];

    public function mount()
    {
        $this->month = now()->month;
        $this->year = now()->year;
    }

    public function export()
    {
        $this->validate();

        $filename = 'Register_Persalinan_' . str_pad($this->month, 2, '0', STR_PAD_LEFT) . '_' . $this->year . '.xlsx';

        return Excel::download(
            new DeliveryRegisterExport($this->month, $this->year),
            $filename
        );
    }

    public function render()
    {
        return view('livewire.export-delivery-register')
            ->layout('layouts.app');
    }
}
