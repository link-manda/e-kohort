<?php

namespace App\Http\Controllers;

use App\Services\MonthlyReportService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class MonthlyReportExportController extends Controller
{
    public function export(Request $request, MonthlyReportService $service)
    {
        $year = $request->input('year', now()->year);
        $month = $request->input('month', now()->month);

        $data = $service->getReportData($year, $month);

        $pdf = Pdf::loadView('reports.monthly-summary-pdf', compact('data'))
            ->setPaper('a4', 'portrait');

        $filename = 'Laporan_Bulanan_' . $year . '_' . str_pad($month, 2, '0', STR_PAD_LEFT) . '.pdf';

        return $pdf->stream($filename);
    }
}
