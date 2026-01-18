<?php

namespace App\Exports;

use App\Models\Child;
use App\Models\ChildVisit;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Carbon\Carbon;

class IndividualImmunizationExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths, WithTitle
{
    protected $childId;
    protected $rowNumber = 1;
    protected $childName;

    public function __construct($childId)
    {
        $this->childId = $childId;
        $child = Child::find($childId);
        $this->childName = $child ? ($child->name ?? $child->full_name ?? 'Unknown') : 'Unknown';
    }

    /**
     * Ambil semua riwayat visit anak ini
     */
    public function collection()
    {
        return ChildVisit::with(['child.patient', 'immunizationActions.vaccine'])
            ->where('child_id', $this->childId)
            ->orderBy('visit_date', 'asc')
            ->get();
    }

    /**
     * Header kolom sesuai permintaan user untuk riwayat individual
     */
    public function headings(): array
    {
        return [
            'NO',
            'TANGGAL KUNJUNGAN',
            'USIA SAAT KUNJUNGAN',
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
            'DETAIL VAKSIN (BATCH / PROVIDER)',
            'PARASETAMOL DROP (DOSIS)',
            'PARASETAMOL SIRUP (DOSIS)',
            'KETERANGAN',
        ];
    }

    /**
     * Mapping data per visit
     */
    public function map($visit): array
    {
        $child = $visit->child;
        $patient = $child->patient ?? null;

        // Usia pada kunjungan (format detail dari model Child)
        $ageAtVisit = method_exists($child, 'getDetailedAge') ? $child->getDetailedAge($visit->visit_date) : ($child->dob ? Carbon::parse($child->dob)->diffInMonths($visit->visit_date) . ' bulan' : '-');

        // Jenis imunisasi (nama atau normalisasi kode)
        $vaccineNames = $visit->immunizationActions->map(function($a) {
            if (isset($a->vaccine) && $a->vaccine && isset($a->vaccine->name)) {
                return $a->vaccine->name;
            }
            return $a->vaccine_type ? str_replace('_', ' ', $a->vaccine_type) : null;
        })->filter()->unique()->values()->toArray();
        $vaccineString = count($vaccineNames) ? implode(', ', $vaccineNames) : '-';

        // Detail vaksin: batch / provider per tindakan
        $vaccineDetails = $visit->immunizationActions->map(function($a) {
            $name = (isset($a->vaccine) && $a->vaccine && isset($a->vaccine->name)) ? $a->vaccine->name : ($a->vaccine_type ? str_replace('_', ' ', $a->vaccine_type) : '-');
            $batch = $a->batch_number ?? '-';
            $provider = $a->provider_name ?? '-';
            return trim("{$name} (Batch: {$batch} / Provider: {$provider})");
        })->filter()->unique()->values()->toArray();
        $vaccineDetailString = count($vaccineDetails) ? implode('; ', $vaccineDetails) : '-';

        // Medicine dosages
        $medicineGiven = $visit->medicine_given ?? '';
        $dropDosage = (stripos($medicineGiven, 'Parasetamol Drop') !== false) ? ($visit->medicine_dosage ?? '-') : '';
        $syrupDosage = (stripos($medicineGiven, 'Parasetamol Sirup') !== false) ? ($visit->medicine_dosage ?? '-') : '';

        return [
            $this->rowNumber++,
            $visit->visit_date ? Carbon::parse($visit->visit_date)->format('d/m/Y') : '-',
            $ageAtVisit,
            $child->name ?? ($child->full_name ?? '-'),
            $child->nik ?? '-',
            $patient->phone ?? $child->parent_phone ?? '-',
            ($patient ? ($patient->name ?? '-') : ($child->mother_name ?? '-')) . ' / ' . ($patient->nik ?? ($child->mother_nik ?? '-')),
            ($child->pob ?? '-') . ( $child->dob ? (', ' . Carbon::parse($child->dob)->format('d/m/Y')) : '' ),
            $visit->weight ? number_format($visit->weight, 1) : '-',
            $visit->height ? number_format($visit->height, 1) : '-',
            $visit->head_circumference ? number_format($visit->head_circumference, 1) : '-',
            $visit->nutritional_status ?? '-',
            $vaccineString,
            $vaccineDetailString,
            $dropDosage,
            $syrupDosage,
            $visit->notes ?? '-',
        ];
    }

    /**
     * Styling
     */
    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => [
                    'bold' => true,
                    'size' => 11,
                    'color' => ['rgb' => 'FFFFFF'],
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '70AD47'],
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
            ],
        ];
    }

    /**
     * Lebar kolom
     */
    public function columnWidths(): array
    {
        return [
            'A' => 5,   // No
            'B' => 18,  // Tanggal
            'C' => 12,  // Usia
            'D' => 10,  // BB
            'E' => 12,  // PB/TB
            'F' => 15,  // Lingkar Kepala
            'G' => 13,  // Status Gizi
            'H' => 30,  // Vaksin
            'I' => 13,  // Parasetamol Drop
            'J' => 13,  // Parasetamol Syr
            'K' => 20,  // Dosis
            'L' => 10,  // Suhu
            'M' => 25,  // Keluhan
            'N' => 25,  // Diagnosis
            'O' => 35,  // Keterangan
        ];
    }

    /**
     * Nama sheet
     */
    public function title(): string
    {
        return substr($this->childName, 0, 31); // Max 31 karakter untuk sheet name
    }
}
