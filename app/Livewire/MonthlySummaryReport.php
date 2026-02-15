<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Patient;
use App\Models\Child;
use App\Models\AncVisit;
use App\Models\Pregnancy;
use App\Models\DeliveryRecord;
use App\Models\PostnatalVisit;
use App\Models\KbVisit;
use App\Models\GeneralVisit;
use App\Models\ChildVisit;
use App\Models\ChildGrowthRecord;
use App\Models\ImmunizationAction;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class MonthlySummaryReport extends Component
{
    public $month;
    public $year;
    public $data = [];

    public function mount()
    {
        $this->month = now()->month;
        $this->year = now()->year;
        $this->generateReport();
    }

    public function updated($propertyName)
    {
        if (in_array($propertyName, ['month', 'year'])) {
            $this->generateReport();
        }
    }

    public function generateReport()
    {
        // Use the service to generate data
        $service = new \App\Services\MonthlyReportService();
        $this->data = $service->getReportData($this->year, $this->month);
    }

    public function render()
    {
        return view('livewire.monthly-summary-report')->layout('layouts.dashboard');
    }
}
