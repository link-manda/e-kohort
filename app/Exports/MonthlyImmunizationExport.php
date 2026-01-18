<?php

namespace App\Exports;

use App\Models\ChildVisit;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Carbon\Carbon;

class MonthlyImmunizationExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths
{
    protected $month;
    protected $year;
    protected $rowNumber = 1;

    public function __construct($month, $year)
    {
        $this->month = $month;
        $this->year = $year;
    }

    /**
     * Ambil data visit berdasarkan bulan dan tahun (eager load relasi penting)
     */
    public function collection()
    {
        return ChildVisit::with(['child.patient', 'immunizationActions.vaccine'])
            ->whereYear('visit_date', $this->year)
            ->whereMonth('visit_date', $this->month)
            ->orderBy('visit_date', 'asc')
            ->get();
    }

    /**
     * Header sesuai permintaan user
     */
    public function headings(): array
    {
        return [
            'NO',
            'NAMA BAYI',
            'NIK ANAK',
            'HP',
            'IDENTITAS IBU (Nama / NIK)',
            'TTL BAYI',
            'BERAT BADAN (kg)',
            'PANJANG (cm)',
            'LINGKAR KEPALA (cm)',
            'STATUS GIZI',
            'JENIS IMUNISASI',
            'PARASETAMOL DROP (DOSIS)',
            'PARASETAMOL SIRUP (DOSIS)',
            'KETERANGAN',
        ];
    }

    /**
     * Mapping data per row sesuai header
     */
    public function map($visit): array
    {
        $child = $visit->child;
        $patient = $child->patient ?? null;

        // Build vaccine list (comma separated)
        $vaccines = $visit->immunizationActions->map(function($a) {
            // Prefer vaccine.name, fallback to vaccine_type normalized
            if (isset($a->vaccine) && $a->vaccine && isset($a->vaccine->name)) {
                return $a->vaccine->name;
            }
            return $a->vaccine_type ? str_replace('_', ' ', $a->vaccine_type) : null;
        })->filter()->unique()->values()->toArray();

        $vaccineString = count($vaccines) ? implode(', ', $vaccines) : '-';

        // Medicine columns
        $medicineGiven = $visit->medicine_given ?? '';
        $dropDosage = (stripos($medicineGiven, 'Parasetamol Drop') !== false) ? ($visit->medicine_dosage ?? '-') : '';
        $syrupDosage = (stripos($medicineGiven, 'Parasetamol Sirup') !== false) ? ($visit->medicine_dosage ?? '-') : '';

        return [
            $this->rowNumber++,
            // Child name: prefer 'name' attribute (existing model), fallback to full_name if present
            $child->name ?? ($child->full_name ?? '-'),
            $child->nik ?? '-',
            $patient->phone ?? $child->parent_phone ?? '-',
            ($patient ? ($patient->name ?? '-') : ($child->mother_name ?? '-')) . " / " . ($patient->nik ?? ($child->mother_nik ?? '-')),
            // TTL: use pob and dob fields from Child model
            ($child->pob ?? '-') . ( $child->dob ? (', ' . Carbon::parse($child->dob)->format('d/m/Y')) : '' ),
            $visit->weight ? number_format($visit->weight, 1) : '-',
            $visit->height ? number_format($visit->height, 1) : '-',
            $visit->head_circumference ? number_format($visit->head_circumference, 1) : '-',
            $visit->nutritional_status ?? '-',
            $vaccineString,
            $dropDosage,
            $syrupDosage,
            $visit->notes ?? '-',
        ];
    }

    /**
     * Styling and header formatting
     */
    public function styles(Worksheet $sheet)
    {
        // Header style
        $sheet->getStyle('A1:N1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '2F75B5'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        // Wrap text for IDENTITAS IBU column
        $sheet->getStyle('E')->getAlignment()->setWrapText(true);

        // Make header row taller
        $sheet->getRowDimension(1)->setRowHeight(30);

        return [];
    }

    /**
     * Column widths
     */
    public function columnWidths(): array
    {
        return [
            'A' => 5,
            'B' => 30,
            'C' => 18,
            'D' => 15,
            'E' => 35,
            'F' => 18,
            'G' => 12,
            'H' => 12,
            'I' => 14,
            'J' => 20,
            'K' => 20,
            'L' => 20,
            'M' => 30,
        ];
    }
}
