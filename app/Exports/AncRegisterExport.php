<?php

namespace App\Exports;

use App\Models\AncVisit;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AncRegisterExport implements WithEvents
{
    protected $filters;

    /**
     * @param array $filters  Filter untuk query data ANC
     */
    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    /**
     * Get data ANC berdasarkan filter
     */
    protected function getData()
    {
        $query = AncVisit::with(['pregnancy.patient', 'labResult'])
            ->orderBy('visit_date', 'asc');

        if (!empty($this->filters['date_from'])) {
            $query->whereDate('visit_date', '>=', $this->filters['date_from']);
        }

        if (!empty($this->filters['date_to'])) {
            $query->whereDate('visit_date', '<=', $this->filters['date_to']);
        }

        if (!empty($this->filters['risk_category'])) {
            $query->where('risk_category', $this->filters['risk_category']);
        }

        if (!empty($this->filters['pregnancy_status'])) {
            $query->whereHas('pregnancy', function ($q) {
                $q->where('status', $this->filters['pregnancy_status']);
            });
        }

        return $query->get();
    }

    /**
     * Register Laravel Excel events
     */
    public function registerEvents(): array
    {
        $templatePath = storage_path('app/templates/Template_Export_Anc.xlsx');

        return [
            AfterSheet::class => function (AfterSheet $event) use ($templatePath) {
                if (!file_exists($templatePath)) {
                    throw new \RuntimeException("Template not found: {$templatePath}");
                }

                /** @var Worksheet $sheet */
                $sheet = $event->sheet->getDelegate();

                // Load template dan copy struktur ke sheet output
                $templateSpreadsheet = IOFactory::load($templatePath);
                $templateSheet = $templateSpreadsheet->getActiveSheet();

                // Copy content dari template (preserves merged cells & styles)
                $sheet->fromArray(
                    $templateSheet->toArray(null, true, true, true),
                    null,
                    'A1'
                );

                // Copy merged cells dari template
                foreach ($templateSheet->getMergeCells() as $mergeRange) {
                    $sheet->mergeCells($mergeRange);
                }

                // Fetch data berdasarkan filter
                $data = $this->getData();

                // Data starts on row 4 (after three header rows in template)
                $startRow = 4;
                $rowCounter = 0;

                foreach ($data as $visit) {
                    $excelRow = $startRow + $rowCounter;

                    $patient = $visit->pregnancy->patient ?? null;
                    $pregnancy = $visit->pregnancy ?? null;
                    $lab = $visit->labResult ?? null;

                    if (!$patient || !$pregnancy) continue;

                    // Kolom A-L: Data Pasien & Kehamilan
                    $sheet->setCellValue("A{$excelRow}", $rowCounter + 1);
                    $sheet->setCellValue("B{$excelRow}", $visit->visit_date ? $visit->visit_date->format('d/m/Y') : '-');
                    $sheet->setCellValue("C{$excelRow}", $patient->no_rm ?? '-');
                    $sheet->setCellValue("D{$excelRow}", $patient->name ?? '-');
                    $sheet->setCellValue("E{$excelRow}", $patient->nik ?? '-');
                    $sheet->setCellValue("F{$excelRow}", $patient->husband_occupation ?? '-');
                    $sheet->setCellValue("G{$excelRow}", $patient->education ?? '-');
                    $sheet->setCellValue("H{$excelRow}", $patient->dob ? $patient->dob->format('d/m/Y') : '-');
                    $sheet->setCellValue("I{$excelRow}", $patient->phone ?? '-');
                    $sheet->setCellValue("J{$excelRow}", $patient->address ?? '-');
                    $sheet->setCellValue("K{$excelRow}", $pregnancy->gravida ?? '-');
                    $sheet->setCellValue("L{$excelRow}", $visit->gestational_age_weeks ?? '-');

                    // Kolom M-S: Kunjungan K1-K6, K8
                    $visitCode = $visit->visit_code;
                    $sheet->setCellValue("M{$excelRow}", $visitCode == 'K1' ? '✓' : '');
                    $sheet->setCellValue("N{$excelRow}", $visitCode == 'K2' ? '✓' : '');
                    $sheet->setCellValue("O{$excelRow}", $visitCode == 'K3' ? '✓' : '');
                    $sheet->setCellValue("P{$excelRow}", $visitCode == 'K4' ? '✓' : '');
                    $sheet->setCellValue("Q{$excelRow}", $visitCode == 'K5' ? '✓' : '');
                    $sheet->setCellValue("R{$excelRow}", $visitCode == 'K6' ? '✓' : '');
                    $sheet->setCellValue("S{$excelRow}", $visitCode == 'K8' ? '✓' : '');

                    // Kolom T: ANC 12T (cek total visits >= 12)
                    $totalVisits = $pregnancy->ancVisits ? $pregnancy->ancVisits()->count() : 0;
                    $sheet->setCellValue("T{$excelRow}", $totalVisits >= 12 ? '✓' : '');

                    // Kolom U-V: Berat Badan
                    $sheet->setCellValue("U{$excelRow}", $visit->weight_before_pregnancy ?? '-');
                    $sheet->setCellValue("V{$excelRow}", $visit->current_weight ?? '-');

                    // Kolom W: TB (Tinggi Badan)
                    $sheet->setCellValue("W{$excelRow}", $visit->height ?? '-');

                    // Kolom X-AA: Status Gizi
                    $sheet->setCellValue("X{$excelRow}", $visit->imt ?? '-');
                    $sheet->setCellValue("Y{$excelRow}", $visit->muac ?? '-');
                    $sheet->setCellValue("Z{$excelRow}", ($visit->muac && $visit->muac < 23.5) ? '✓' : '');
                    $sheet->setCellValue("AA{$excelRow}", ($visit->muac && $visit->muac >= 23.5) ? '✓' : '');

                    // Kolom AB-AE: Pemeriksaan Fisik
                    $sheet->setCellValue("AB{$excelRow}", $visit->blood_pressure ?? '-');
                    $sheet->setCellValue("AC{$excelRow}", $visit->fundal_height ?? '-');
                    $sheet->setCellValue("AD{$excelRow}", $visit->fetal_heart_rate ?? '-');
                    $sheet->setCellValue("AE{$excelRow}", $visit->fetal_position ?? '-');

                    // Kolom AF-AH: Imunisasi & TTD
                    $sheet->setCellValue("AF{$excelRow}", $visit->tt_immunization ?? '-');
                    $sheet->setCellValue("AG{$excelRow}", $visit->ttd_given ? '✓' : '');
                    $sheet->setCellValue("AH{$excelRow}", $visit->ttd_amount ?? '-');

                    // Kolom AI-AM: Laboratorium
                    $sheet->setCellValue("AI{$excelRow}", $lab->hiv_test_result ?? '-');
                    $sheet->setCellValue("AJ{$excelRow}", $lab->syphilis_test_result ?? '-');
                    $sheet->setCellValue("AK{$excelRow}", $lab->hbsag_test_result ?? '-');
                    $sheet->setCellValue("AL{$excelRow}", $lab->hemoglobin ?? '-');
                    $sheet->setCellValue("AM{$excelRow}", $lab->protein_urine ?? '-');

                    // Kolom AN: Golongan Darah
                    $sheet->setCellValue("AN{$excelRow}", $patient->blood_type ?? '-');

                    // Kolom AO-AR: Status Anemia (Tidak/Ringan/Sedang/Berat)
                    $hb = $lab->hemoglobin ?? 0;
                    $sheet->setCellValue("AO{$excelRow}", $hb >= 11 ? '✓' : '');
                    $sheet->setCellValue("AP{$excelRow}", ($hb >= 10 && $hb < 11) ? '✓' : '');
                    $sheet->setCellValue("AQ{$excelRow}", ($hb >= 8 && $hb < 10) ? '✓' : '');
                    $sheet->setCellValue("AR{$excelRow}", ($hb < 8 && $hb > 0) ? '✓' : '');

                    // Kolom AS-AW: USG, Konseling, Deteksi Risiko, Rujukan, Diagnosa
                    $sheet->setCellValue("AS{$excelRow}", $visit->usg_done ? 'Ya' : 'Tidak');
                    $sheet->setCellValue("AT{$excelRow}", $visit->counseling_done ? '✓' : '');
                    $sheet->setCellValue("AU{$excelRow}", $visit->risk_category ?? '-');
                    $sheet->setCellValue("AV{$excelRow}", $visit->referral_needed ? 'Ya' : 'Tidak');
                    $sheet->setCellValue("AW{$excelRow}", $visit->diagnosis ?? '-');

                    // Kolom AX-AY: Tindak Lanjut & Nama Nakes
                    $sheet->setCellValue("AX{$excelRow}", $visit->follow_up_plan ?? '-');
                    $sheet->setCellValue("AY{$excelRow}", $visit->created_by ?? '-');

                    $rowCounter++;
                }
            },
        ];
    }
}