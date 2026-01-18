<?php

namespace App\Http\Controllers;

use App\Exports\MonthlyImmunizationExport;
use App\Exports\IndividualImmunizationExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ImmunizationExportController extends Controller
{
    /**
     * Export laporan imunisasi bulanan
     */
    public function monthly(Request $request)
    {
        $request->validate([
            'month' => 'required|integer|between:1,12',
            'year' => 'required|integer|min:2020|max:' . (date('Y') + 1),
        ]);

        $month = $request->input('month');
        $year = $request->input('year');

        $monthName = Carbon::create($year, $month)->locale('id')->translatedFormat('F');
        $fileName = "Laporan_Imunisasi_{$monthName}_{$year}.xlsx";

        return Excel::download(new MonthlyImmunizationExport($month, $year), $fileName);
    }

    /**
     * Export riwayat imunisasi individual per anak
     */
    public function individual(Request $request)
    {
        $request->validate([
            'child_id' => 'required|exists:children,id',
        ]);

        $childId = $request->input('child_id');

        // Ambil nama anak untuk filename
        $child = \App\Models\Child::find($childId);
        $childName = str_replace(' ', '_', $child->full_name);
        $fileName = "Riwayat_Imunisasi_{$childName}.xlsx";

        return Excel::download(new IndividualImmunizationExport($childId), $fileName);
    }
}
