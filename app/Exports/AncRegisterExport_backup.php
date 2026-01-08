<?php

namespace App\Exports;

use App\Models\AncVisit;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Contracts\View\View;

class AncRegisterExport implements FromView, ShouldAutoSize
{
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    /**
     * Return view with data for Excel export
     */
    public function view(): View
    {
        $query = AncVisit::query()
            ->with(['pregnancy.patient', 'labResult'])
            ->whereHas('pregnancy.patient');

        // Filter by date range
        if (!empty($this->filters['date_from'])) {
            $query->where('visit_date', '>=', $this->filters['date_from']);
        }
        if (!empty($this->filters['date_to'])) {
            $query->where('visit_date', '<=', $this->filters['date_to']);
        }

        // Filter by pregnancy status
        if (!empty($this->filters['pregnancy_status']) && $this->filters['pregnancy_status'] !== 'all') {
            $query->whereHas('pregnancy', function ($q) {
                $q->where('status', $this->filters['pregnancy_status']);
            });
        }

        // Filter by risk category
        if (!empty($this->filters['risk_category']) && $this->filters['risk_category'] !== 'all') {
            $query->where('risk_category', $this->filters['risk_category']);
        }

        $visits = $query->orderBy('visit_date', 'desc')->get();

        return view('exports.anc_register', [
            'visits' => $visits
        ]);
    }
}
