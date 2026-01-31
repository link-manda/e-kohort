<!DOCTYPE html>
<html lang="id">

<head>
    <!-- PERBAIKAN UTAMA: Gunakan Meta Tag Lengkap agar DOMDocument bisa baca -->
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Register ANC</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #000000;
            padding: 5px;
            vertical-align: middle;
            font-size: 11px;
            font-family: Arial, sans-serif;
        }

        th {
            text-align: center;
            font-weight: bold;
            background-color: #f2f2f2;
        }

        .center {
            text-align: center;
        }

        .bg-grey {
            background-color: #e0e0e0;
        }
    </style>
</head>

<body>
    <table>
        <thead>
            <!-- JUDUL -->
            <tr>
                <th colspan="58" style="font-size: 14px; padding: 10px;">
                    REGISTER ANTENATAL CARE (ANC) TERINTEGRASI<br>
                    PERIODE: {{ strtoupper($period ?? '-') }}
                </th>
            </tr>

            <!-- BARIS HEADER 1 -->
            <tr>
                <th rowspan="2">NO</th>
                <th rowspan="2">TANGGAL<br>KUNJUNGAN</th>
                <th rowspan="2">NO RM</th>
                <th rowspan="2">NO KK</th>
                <th rowspan="2">NO BPJS</th>

                <!-- DATA IBU (6 Kolom) -->
                <th colspan="6">IDENTITAS IBU</th>

                <!-- DATA SUAMI (6 Kolom) -->
                <th colspan="6">IDENTITAS SUAMI</th>

                <th rowspan="2">ALAMAT LENGKAP</th>

                <!-- RIWAYAT (4 Kolom) -->
                <th rowspan="2">GRAVIDA</th>
                <th rowspan="2">HPHT</th>
                <th rowspan="2">UK<br>(Minggu)</th>
                <th rowspan="2">JARAK<br>(Thn)</th>

                <!-- KUNJUNGAN (8 Kolom) -->
                <th colspan="8">STATUS KUNJUNGAN</th>

                <!-- FISIK (3 Kolom) -->
                <th colspan="2">BERAT BADAN</th>
                <th rowspan="2">TB<br>(cm)</th>

                <!-- GIZI (4 Kolom) -->
                <th colspan="4">STATUS GIZI</th>

                <!-- VITAL SIGNS (5 Kolom) -->
                <th rowspan="2">TD<br>(mmHg)</th>
                <th rowspan="2">MAP</th>
                <th rowspan="2">TFU<br>(cm)</th>
                <th rowspan="2">DJJ</th>
                <th rowspan="2">LETAK<br>JANIN</th>

                <!-- TINDAKAN (2 Kolom) -->
                <th rowspan="2">TT</th>
                <th rowspan="2">TTD</th>

                <!-- LAB (6 Kolom) -->
                <th colspan="6">LABORATORIUM</th>

                <!-- ANEMIA (4 Kolom) -->
                <th colspan="4">STATUS ANEMIA</th>

                <!-- LAINNYA (7 Kolom) -->
                <th rowspan="2">USG <br> (Sudah/Belum)</th>
                <th rowspan="2">KIE</th>
                <th rowspan="2">RESIKO</th>
                <th rowspan="2">RUJUKAN <br> (Ya/Tidak)</th>
                <th rowspan="2">DIAGNOSA</th>
                <th rowspan="2">TINDAK<br>LANJUT</th>
                <th rowspan="2">NAKES</th>
            </tr>

            <!-- BARIS HEADER 2 (Sub-Kolom) -->
            <tr>
                <!-- IBU -->
                <th>NAMA</th>
                <th>NIK</th>
                <th>PEKERJAAN</th>
                <th>PENDIDIKAN</th>
                <th>UMUR</th>
                <th>HP</th>

                <!-- SUAMI -->
                <th>NAMA</th>
                <th>NIK</th>
                <th>PEKERJAAN</th>
                <th>PENDIDIKAN</th>
                <th>UMUR</th>
                <th>HP</th>

                <!-- KUNJUNGAN -->
                <th>K1</th>
                <th>K2</th>
                <th>K3</th>
                <th>K4</th>
                <th>K5</th>
                <th>K6</th>
                <th>K8</th>
                <th>12T</th>

                <!-- BB -->
                <th>AWAL</th>
                <th>KINI</th>

                <!-- GIZI -->
                <th>IMT</th>
                <th>LILA</th>
                <th>KEK</th>
                <th>NORMAL</th>

                <!-- LAB -->
                <th>HIV</th>
                <th>SIFILIS</th>
                <th>HBsAg</th>
                <th>HB</th>
                <th>PROT.U</th>
                <th>GOLDA <br> Ibu/Suami</th>

                <!-- ANEMIA -->
                <th>TIDAK <br> ANEMIA</th>
                <th>ANEMIA <br> RINGAN</th>
                <th>ANEMIA <br> SEDANG</th>
                <th>ANEMIA <br> BERAT</th>
            </tr>
        </thead>
        <tbody>
            @forelse($visits as $index => $visit)
                @php
                    $patient = $visit->pregnancy->patient;
                    $preg = $visit->pregnancy;
                    $lab = $visit->labResult;

                    // Logic Gizi
                    $isKek = $visit->lila && $visit->lila < 23.5;

                    // Logic Anemia
                    $hb = $lab->hb ?? 0;
                @endphp
                <tr>
                    <td class="center">{{ $index + 1 }}</td>
                    <td class="center">
                        {{ $visit->visit_date ? \Carbon\Carbon::parse($visit->visit_date)->format('d/m/Y') : '-' }}</td>
                    <td>'{{ $patient->no_rm ?? '-' }}</td> <!-- Kasih kutip biar gak jadi scientific number -->
                    <td>'{{ $patient->no_kk ?? '-' }}</td>
                    <td>'{{ $patient->no_bpjs ?? '-' }}</td>

                    <!-- IBU -->
                    <td>{{ $patient->name ?? '-' }}</td>
                    <td>'{{ $patient->nik ?? '-' }}</td>
                    <td>{{ $patient->job ?? '-' }}</td>
                    <td>{{ $patient->education ?? '-' }}</td>
                    <td class="center">{{ $patient->dob ? \Carbon\Carbon::parse($patient->dob)->age : '-' }}</td>
                    <td>'{{ $patient->phone ?? '-' }}</td>

                    <!-- SUAMI -->
                    <td>{{ $patient->husband_name ?? '-' }}</td>
                    <td>'{{ $patient->husband_nik ?? '-' }}</td>
                    <td>{{ $patient->husband_job ?? '-' }}</td>
                    <td>{{ $patient->husband_education ?? '-' }}</td>
                    <td class="center">-</td> <!-- Umur Suami biasanya tidak dicatat di DB, set strip -->
                    <td>-</td>

                    <td>{{ $patient->address ?? '-' }}</td>

                    <td class="center">{{ $preg->gravida ?? '-' }}</td>
                    <td class="center">{{ $preg->hpht ? \Carbon\Carbon::parse($preg->hpht)->format('d/m/Y') : '-' }}
                    </td>
                    <td class="center">{{ $visit->gestational_age ?? '-' }}</td>
                    <td class="center">{{ $preg->pregnancy_gap ?? '-' }}</td>

                    <!-- KUNJUNGAN -->
                    <td class="center">{{ $visit->visit_code == 'K1' ? '✓' : '' }}</td>
                    <td class="center">{{ $visit->visit_code == 'K2' ? '✓' : '' }}</td>
                    <td class="center">{{ $visit->visit_code == 'K3' ? '✓' : '' }}</td>
                    <td class="center">{{ $visit->visit_code == 'K4' ? '✓' : '' }}</td>
                    <td class="center">{{ $visit->visit_code == 'K5' ? '✓' : '' }}</td>
                    <td class="center">{{ $visit->visit_code == 'K6' ? '✓' : '' }}</td>
                    <td class="center"></td>
                    <td class="center">{{ $visit->anc_12t ? '✓' : '' }}</td>

                    <!-- FISIK -->
                    <td class="center">{{ $preg->weight_before ?? '-' }}</td>
                    <td class="center">{{ $visit->weight ?? '-' }}</td>
                    <td class="center">{{ $preg->height ?? '-' }}</td>

                    <!-- GIZI -->
                    <td class="center">{{ $visit->bmi ?? '-' }}</td>
                    <td class="center">{{ $visit->lila ?? '-' }}</td>
                    <td class="center">{{ $isKek ? '✓' : '' }}</td>
                    <td class="center">{{ !$isKek && $visit->lila ? '✓' : '' }}</td>

                    <!-- VITAL -->
                    <td class="center">{{ $visit->systolic }}/{{ $visit->diastolic }}</td>
                    <td class="center" style="color: {{ $visit->map_score > 100 ? 'red' : 'black' }}">
                        {{ $visit->map_score }}</td>
                    <td class="center">{{ $visit->tfu ?? '-' }}</td>
                    <td class="center">{{ $visit->djj ?? '-' }}</td>
                    <td class="center">{{ $visit->fetal_presentation ?? '-' }}</td>

                    <td class="center">{{ $visit->tt_immunization ?? '-' }}</td>
                    <td class="center">{{ $visit->fe_tablets ?? '-' }}</td>

                    <!-- LAB -->
                    <td class="center">{{ $visit->hiv_status ?? 'NR' }}</td>
                    <td class="center">{{ $visit->syphilis_status ?? 'NR' }}</td>
                    <td class="center">{{ $visit->hbsag_status ?? 'NR' }}</td>
                    <td class="center">{{ $visit->hb ?? '-' }}</td>
                    <td class="center">{{ $visit->protein_urine ?? 'Negatif' }}</td>
                    <td class="center">{{ $patient->blood_type ?? '-' }} / {{ $patient->husband_blood_type ?? '-' }}
                    </td>

                    <!-- ANEMIA -->
                    <td class="center">{{ $visit->hb >= 11 ? '✓' : '' }}</td>
                    <td class="center">{{ $visit->hb >= 9 && $visit->hb < 11 ? '✓' : '' }}</td>
                    <td class="center">{{ $visit->hb >= 7 && $visit->hb < 9 ? '✓' : '' }}</td>
                    <td class="center">{{ $visit->hb > 0 && $visit->hb < 7 ? '✓' : '' }}</td>

                    <!-- LAINNYA -->
                    <td class="center">{{ $visit->usg_check ? 'Sudah' : 'Belum' }}</td>
                    <td class="center">{{ $visit->counseling_check ? '✓' : '' }}</td>
                    <td class="center">{{ $visit->risk_level ?? '-' }}</td>
                    <td class="center">{{ $visit->referral_target ? 'Ya' : 'Tidak' }}</td>
                    <td>{{ $visit->diagnosis ?? '-' }}</td>
                    <td>{{ $visit->follow_up ?? '-' }}</td>
                    <td>{{ $visit->midwife_name ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="58" style="text-align: center; padding: 20px;">Data Tidak Ditemukan</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>
