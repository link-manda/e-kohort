<?php

namespace App\Exports;

use App\Models\AncVisit;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeExport;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Carbon\Carbon;

class AncRegisterExport implements WithEvents
{
    protected $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    public function registerEvents(): array
    {
        return [
            BeforeExport::class => function (BeforeExport $event) {
                // 1. Load Template Excel dari Storage
                $templatePath = storage_path('app/templates/Register_ANC_Terintegrasi.xlsx');

                if (!file_exists($templatePath)) {
                    throw new \Exception("File template tidak ditemukan di: " . $templatePath);
                }

                // Load Template ke memory
                $templateSpreadsheet = IOFactory::load($templatePath);
                $templateSheet = $templateSpreadsheet->getActiveSheet();

                // 2. Akses Spreadsheet Internal Laravel Excel
                $internalSpreadsheet = $event->writer->getDelegate();

                // 3. Teknik SWAPPING (Updated - Safer)
                // Masukkan dulu sheet dari template kita
                $internalSpreadsheet->addExternalSheet($templateSheet);

                // Cek jumlah sheet sekarang
                // Jika > 1, berarti ada sheet kosong default yang harus dibuang (biasanya di index 0)
                if ($internalSpreadsheet->getSheetCount() > 1) {
                    $internalSpreadsheet->removeSheetByIndex(0);
                }

                // Set sheet aktif ke sheet pertama (sheet template kita)
                $internalSpreadsheet->setActiveSheetIndex(0);

                // Ambil referensi sheet aktif untuk mulai mengisi data
                $sheet = $internalSpreadsheet->getActiveSheet();

                // 4. Ambil Data
                $visits = $this->getQueryData();

                // 5. Mulai Mengisi Data (Mulai Baris 4)
                $row = 4;
                $no = 1;

                foreach ($visits as $visit) {
                    $patient = $visit->pregnancy->patient;
                    $preg = $visit->pregnancy;

                    // --- KOLOM A: No ---
                    $sheet->setCellValue('A' . $row, $no++);

                    // --- KOLOM B: Tanggal Kunjungan ---
                    $sheet->setCellValue('B' . $row, $visit->visit_date ? Carbon::parse($visit->visit_date)->format('d/m/Y') : '');

                    // --- KOLOM C: No RM / KK / BPJS (Bertumpuk) ---
                    $colC = ($patient->no_rm ?? '') . "\n" .
                        ($patient->no_kk ?? '') . "\n" .
                        ($patient->no_bpjs ?? '');
                    $sheet->setCellValue('C' . $row, $colC);

                    // --- KOLOM D: Nama Ibu / Suami ---
                    $colD = ($patient->name ?? '') . "/" .
                        ($patient->husband_name ?? '');
                    $sheet->setCellValue('D' . $row, $colD);

                    // --- KOLOM E: NIK Ibu / Suami ---
                    $colE = ($patient->nik ?? '-') . "/" .
                        ($patient->husband_nik ?? '-');
                    $sheet->setCellValue('E' . $row, $colE);

                    // --- KOLOM F: Pekerjaan ---
                    $colF = ($patient->job ?? '') . "/" .
                        ($patient->husband_job ?? '');
                    $sheet->setCellValue('F' . $row, $colF);

                    // --- KOLOM G: Pendidikan ---
                    $colG = ($patient->education ?? '') . "|" .
                        ($patient->husband_education ?? '');
                    $sheet->setCellValue('G' . $row, $colG);

                    // --- KOLOM H: Umur / TTL ---
                    $age = $patient->dob ? Carbon::parse($patient->dob)->age . ' Thn' : '-';
                    $ttl = ($patient->pob ?? '-') . ', ' . ($patient->dob ? Carbon::parse($patient->dob)->format('d-m-Y') : '');
                    $sheet->setCellValue('H' . $row, $age . "/" . $ttl);

                    // --- KOLOM I: No HP ---
                    $sheet->setCellValue('I' . $row, $patient->phone ?? '-');

                    // --- KOLOM J: Alamat (Domisili) ---
                    $sheet->setCellValue('J' . $row, $patient->address ?? '-');

                    // --- KOLOM K: Gravida ---
                    $sheet->setCellValue('K' . $row, $preg->gestational_age ?? '-');

                    // --- KOLOM L: HPHT ---
                    $sheet->setCellValue('L' . $row, $preg->pregnancy_gap);

                    // --- KOLOM O - U: Checklis Kunjungan (K1-K6) ---
                    $sheet->setCellValue('M' . $row, $visit->visit_code == 'K1' ? '✔' : '');
                    $sheet->setCellValue('N' . $row, $visit->visit_code == 'K2' ? '✔' : '');
                    $sheet->setCellValue('O' . $row, $visit->visit_code == 'K3' ? '✔' : '');
                    $sheet->setCellValue('P' . $row, $visit->visit_code == 'K4' ? '✔' : '');
                    $sheet->setCellValue('Q' . $row, $visit->visit_code == 'K5' ? '✔' : '');
                    $sheet->setCellValue('R' . $row, $visit->visit_code == 'K6' ? '✔' : '');
                    $sheet->setCellValue('S' . $row, $visit->visit_code == 'K8' ? '✔' : '');
                    // --- KOLOM V: ANC 12T ---
                    $sheet->setCellValue('T' . $row, $visit->anc_12t ? '✔' : '');

                    // --- KOLOM M: UK (Minggu) ---
                    $sheet->setCellValue('U' . $row, $preg->weight_before);

                    // --- KOLOM N: Jarak Kehamilan (Tahun) ---
                    $sheet->setCellValue('V' . $row, $visit->weight);

                    // --- KOLOM W: BB Sebelum Hamil ---
                    $sheet->setCellValue('W' . $row, $visit->height);

                    // --- KOLOM X: BB Saat Ini ---
                    $sheet->setCellValue('X' . $row, $visit->bmi);

                    // --- KOLOM AB-AC: Status Gizi (KEK/Normal) ---
                    $isKek = ($visit->lila && $visit->lila < 23.5);
                    $sheet->setCellValue('Y' . $row, $isKek ? '✔' : '');
                    $sheet->setCellValue('Z' . $row, !$isKek && $visit->lila ? '✔' : '');

                    // --- KOLOM AD: TD (Tensi) ---
                    $sheet->setCellValue('AB' . $row, ($visit->systolic ?? '-') . '/' . ($visit->diastolic ?? '-'));

                    // --- KOLOM AA: LILA ---
                    $sheet->setCellValue('AC' . $row, $visit->tfu ?? '-');


                    // --- KOLOM AD: TD (Tensi) ---
                    $sheet->setCellValue('AD' . $row, $visit->djj ?? '-');

                    // --- KOLOM AE: MAP SCORE (New) ---
                    $sheet->setCellValue('AE' . $row, $visit->fetal_presentation ?? '-');

                    // --- KOLOM AF: TFU ---
                    $sheet->setCellValue('AF' . $row, $visit->tt_immunization ?? '-');

                    // --- KOLOM AG: DJJ ---
                    $sheet->setCellValue('AG' . $row, $visit->fe_tablets ?? '-');

                    // --- KOLOM AH: Letak Janin ---
                    $sheet->setCellValue('AH' . $row, $visit->hiv_status ?? '-');

                    // --- KOLOM AI: Imunisasi TT ---
                    $sheet->setCellValue('AI' . $row, $visit->syphilis_status ?? '-');

                    // --- KOLOM AJ: TTD ---
                    $sheet->setCellValue('AJ' . $row, $visit->hbsag_status ?? '-');

                    // --- KOLOM AK-AM: Lab Results (from anc_visits) ---
                    $sheet->setCellValue('AK' . $row, $visit->hb ?? '-');
                    $sheet->setCellValue('AL' . $row, $visit->protein_urine ?? '-');
                    $sheet->setCellValue('AM' . $row, ($patient->blood_type ?? '-') . '/' . ($patient->husband_blood_type ?? '-'));


                    // --- KOLOM AQ-AT: Status Anemia ---
                    $hbVal = $visit->hb ?? 0;
                    $sheet->setCellValue('AN' . $row, $hbVal >= 11 ? '✔' : ''); // Normal
                    $sheet->setCellValue('AO' . $row, ($hbVal >= 9 && $hbVal < 11) ? '✔' : ''); // Ringan
                    $sheet->setCellValue('AP' . $row, ($hbVal >= 7 && $hbVal < 9) ? '✔' : ''); // Sedang
                    $sheet->setCellValue('AQ' . $row, ($hbVal > 0 && $hbVal < 7) ? '✔' : ''); // Berat

                    // --- KOLOM AU: USG ---
                    $sheet->setCellValue('AR' . $row, $visit->usg_check ? 'Ya' : 'Tidak');

                    // --- KOLOM AV: Konseling ---
                    $sheet->setCellValue('AS' . $row, $visit->counseling_check ? '✔' : '');

                    // --- KOLOM AW: Resiko ---
                    $sheet->setCellValue('AT' . $row, $visit->risk_level ?? '-');

                    // --- KOLOM AX: Rujukan ---
                    $sheet->setCellValue('AU' . $row, $visit->referral_target ? 'Ya' : 'Tidak');

                    // --- KOLOM AY: Diagnosa ---
                    $sheet->setCellValue('AV' . $row, $visit->diagnosis ?? '-');

                    // --- KOLOM AZ: Tindak Lanjut ---
                    $sheet->setCellValue('AW' . $row, $visit->follow_up ?? '-');

                    // --- KOLOM BA: Nama Nakes ---
                    $sheet->setCellValue('AX' . $row, $visit->midwife_name ?? '-');

                    $row++;
                }
            },
        ];
    }

    /**
     * Query Data Logic
     */
    protected function getQueryData()
    {
        $query = AncVisit::query()
            ->with(['pregnancy.patient'])
            ->whereHas('pregnancy.patient');

        if (!empty($this->filters['date_from'])) {
            $query->where('visit_date', '>=', $this->filters['date_from']);
        }
        if (!empty($this->filters['date_to'])) {
            $query->where('visit_date', '<=', $this->filters['date_to']);
        }

        return $query->orderBy('visit_date', 'asc')->get();
    }
}