<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }

        th {
            border: 1px solid #000000;
            text-align: center;
            vertical-align: middle;
            font-weight: bold;
            background-color: #f2f2f2;
        }

        td {
            border: 1px solid #000000;
            vertical-align: top;
            padding: 5px;
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
            <!-- BARIS HEADER 1 -->
            <tr>
                <th rowspan="3" style="width: 50px;">No</th>
                <th rowspan="3" style="width: 100px;">Tanggal<br>Kunjungan</th>

                <!-- Identitas Stacked Header Row 1 -->
                <th>No RM</th>
                <th>Nama Ibu / Suami</th>
                <th>NIK Ibu / Suami</th>
                <th>Pekerjaan Ibu/Suami</th>
                <th>Pendidikan Ibu/Suami</th>
                <th>Umur</th>
                <th>No HP Ibu/Suami</th>
                <th>Alamat Lengkap (KTP Domisili)</th>

                <!-- Riwayat -->
                <th rowspan="3">Gravida</th>
                <th rowspan="3">HPHT</th>

                <!-- Kunjungan Group -->
                <th colspan="9">KUNJUNGAN DAN HASIL PEMERIKSAAN</th>

                <!-- Fisik Group -->
                <th colspan="2">Berat Badan</th>
                <th rowspan="2">TB (cm)</th>
                <th colspan="4">STATUS GIZI</th>

                <!-- Vital Signs -->
                <th rowspan="3">TD (mmHg)</th>
                <th rowspan="3">MAP Score</th> <!-- Kolom Baru -->
                <th rowspan="3">TFU (cm)</th>
                <th rowspan="3">DJJ</th>
                <th rowspan="3">Letak Janin</th>

                <!-- Tindakan -->
                <th rowspan="3">Imunisasi TT</th>
                <th rowspan="3">TTD (Jml)</th>

                <!-- Laboratorium -->
                <th colspan="6">LABORATORIUM</th>

                <!-- Anemia -->
                <th colspan="4">Status Anemia</th>

                <!-- Akhir -->
                <th rowspan="3">USG (Ya/Tdk)</th>
                <th rowspan="3">Konseling/KIE</th>
                <th rowspan="3">Deteksi Risiko</th>
                <th rowspan="3">Rujukan (Ya/Tdk)</th>
                <th rowspan="3">Diagnosa</th>
                <th rowspan="3">Tindak Lanjut</th>
                <th rowspan="3">Nama Nakes</th>
            </tr>

            <!-- BARIS HEADER 2 -->
            <tr>
                <!-- Identitas Stacked Header Row 2 -->
                <th>NO KK</th>
                <th class="bg-grey">-</th>
                <th class="bg-grey">-</th>
                <th class="bg-grey">-</th>
                <th class="bg-grey">-</th>
                <th>TTL</th>
                <th class="bg-grey">-</th>
                <th>Domisili</th>

                <!-- Sub Kunjungan -->
                <th rowspan="2">UK (Mgg)</th>
                <th rowspan="2">JARAK (Thn)</th>
                <th>K1</th>
                <th>K2</th>
                <th>K3</th>
                <th>K4</th>
                <th>K5</th>
                <th>K6</th>
                <th>K8</th> <!-- Jika diperlukan -->

                <!-- Sub Fisik BB -->
                <th>Sebelum Hamil</th>
                <th>Saat Ini</th>

                <!-- Sub Gizi -->
                <th rowspan="2">IMT</th>
                <th rowspan="2">LILA</th>
                <th rowspan="2">KEK</th>
                <th rowspan="2">NORMAL</th>

                <!-- Sub Lab -->
                <th rowspan="2">HIV</th>
                <th rowspan="2">SIFILIS</th>
                <th rowspan="2">HBSAG</th>
                <th rowspan="2">HB</th>
                <th rowspan="2">PROTEIN URINE</th>
                <th rowspan="2">GOLDA Ibu/Suami</th>

                <!-- Sub Anemia -->
                <th rowspan="2">Tidak Anemia</th>
                <th rowspan="2">Ringan</th>
                <th rowspan="2">Sedang</th>
                <th rowspan="2">Berat</th>
            </tr>

            <!-- BARIS HEADER 3 -->
            <tr>
                <!-- Identitas Stacked Header Row 3 -->
                <th>NO BPJS</th>
                <th class="bg-grey">-</th>
                <th class="bg-grey">-</th>
                <th class="bg-grey">-</th>
                <th class="bg-grey">-</th>
                <th class="bg-grey">-</th>
                <th class="bg-grey">-</th>
                <th class="bg-grey">-</th>

                <!-- Kode Kunjungan & 12T -->
                <th>Trimester 1</th>
                <th>Trimester 1</th>
                <th>Trimester 2</th>
                <th>Trimester 3</th>
                <th>Trimester 3</th>
                <th>Trimester 3</th>
                <th>Lainnya</th>

                <!-- BB Spacers -->
                <th class="bg-grey">-</th>
                <th class="bg-grey">-</th>
                <th class="bg-grey">-</th> <!-- TB Spacer -->
            </tr>
        </thead>
        <tbody>
            @foreach ($visits as $index => $visit)
                <tr>
                    <td class="center">{{ $index + 1 }}</td>
                    <td class="center">
                        {{ $visit->visit_date ? \Carbon\Carbon::parse($visit->visit_date)->format('d/m/Y') : '-' }}</td>

                    <!-- Kolom Identitas Bertumpuk (Menggunakan Break Line <br>) -->
                    <td>
                        {{ $visit->pregnancy->patient->no_rm ?? '-' }}<br>
                        {{ $visit->pregnancy->patient->no_kk ?? '-' }}<br>
                        {{ $visit->pregnancy->patient->no_bpjs ?? '-' }}
                    </td>
                    <td>
                        {{ $visit->pregnancy->patient->name ?? '-' }}<br>
                        {{ $visit->pregnancy->patient->husband_name ?? '-' }}
                    </td>
                    <td>
                        '{{ $visit->pregnancy->patient->nik ?? '-' }}<br>
                        <!-- Tanda kutip agar excel tidak format scientific -->
                        '{{ $visit->pregnancy->patient->husband_nik ?? '-' }}
                    </td>
                    <td>
                        {{ $visit->pregnancy->patient->job ?? '-' }}<br>
                        {{ $visit->pregnancy->patient->husband_job ?? '-' }}
                    </td>
                    <td>
                        {{ $visit->pregnancy->patient->education ?? '-' }}<br>
                        {{ $visit->pregnancy->patient->husband_education ?? '-' }}
                    </td>
                    <td class="center">
                        {{ \Carbon\Carbon::parse($visit->pregnancy->patient->dob)->age }} Thn<br>
                        {{ $visit->pregnancy->patient->pob }}, {{ $visit->pregnancy->patient->dob }}
                    </td>
                    <td>
                        {{ $visit->pregnancy->patient->phone ?? '-' }}
                    </td>
                    <td>
                        {{ $visit->pregnancy->patient->address ?? '-' }}
                    </td>

                    <!-- Riwayat -->
                    <td class="center">{{ $visit->pregnancy->gravida ?? '-' }}</td>
                    <td class="center">{{ $visit->pregnancy->hpht ?? '-' }}</td>

                    <!-- Kunjungan Details -->
                    <td class="center">{{ $visit->gestational_age }}</td>
                    <td class="center">{{ $visit->pregnancy->pregnancy_gap ?? '-' }}</td>

                    <!-- Checkmark Logic utk K1-K6 -->
                    <td class="center">{{ $visit->visit_code == 'K1' ? 'V' : '' }}</td>
                    <td class="center">{{ $visit->visit_code == 'K2' ? 'V' : '' }}</td>
                    <td class="center">{{ $visit->visit_code == 'K3' ? 'V' : '' }}</td>
                    <td class="center">{{ $visit->visit_code == 'K4' ? 'V' : '' }}</td>
                    <td class="center">{{ $visit->visit_code == 'K5' ? 'V' : '' }}</td>
                    <td class="center">{{ $visit->visit_code == 'K6' ? 'V' : '' }}</td>
                    <td class="center"></td> <!-- K8/Lainnya -->

                    <!-- Fisik -->
                    <td class="center">{{ $visit->pregnancy->weight_before ?? '-' }}</td>
                    <td class="center">{{ $visit->weight }}</td>
                    <td class="center">{{ $visit->pregnancy->height ?? '-' }}</td>

                    <!-- Gizi (IMT / LILA) -->
                    <td class="center">{{ $visit->bmi }}</td>
                    <td class="center">{{ $visit->lila }}</td>
                    <td class="center">{{ $visit->lila < 23.5 ? 'V' : '' }}</td> <!-- KEK Check -->
                    <td class="center">{{ $visit->lila >= 23.5 ? 'V' : '' }}</td> <!-- Normal Check -->

                    <!-- Tensi & MAP -->
                    <td class="center">{{ $visit->systolic }}/{{ $visit->diastolic }}</td>
                    <td class="center"
                        style="font-weight: bold; color: {{ $visit->map_score > 100 ? 'red' : 'black' }};">
                        {{ $visit->map_score }}
                    </td>

                    <td class="center">{{ $visit->tfu }}</td>
                    <td class="center">{{ $visit->djj }}</td>
                    <td class="center">{{ $visit->fetal_presentation }}</td>

                    <td class="center">{{ $visit->tt_imunization }}</td>
                    <td class="center">{{ $visit->fe_tablets }}</td>

                    <!-- Lab Results -->
                    <td class="center">{{ $visit->labResult->hiv_status ?? '-' }}</td>
                    <td class="center">{{ $visit->labResult->syphilis_status ?? '-' }}</td>
                    <td class="center">{{ $visit->labResult->hbsag_status ?? '-' }}</td>
                    <td class="center">{{ $visit->labResult->hb ?? '-' }}</td>
                    <td class="center">{{ $visit->labResult->protein_urine ?? '-' }}</td>
                    <td class="center">
                        {{ $visit->pregnancy->patient->blood_type ?? '-' }} /
                        {{ $visit->pregnancy->patient->husband_blood_type ?? '-' }}
                    </td>

                    <!-- Anemia Status (Logic dari HB) -->
                    @php $hb = $visit->labResult->hb ?? 0; @endphp
                    <td class="center">{{ $hb >= 11 ? 'V' : '' }}</td>
                    <td class="center">{{ $hb >= 9 && $hb < 11 ? 'V' : '' }}</td>
                    <td class="center">{{ $hb >= 7 && $hb < 9 ? 'V' : '' }}</td>
                    <td class="center">{{ $hb > 0 && $hb < 7 ? 'V' : '' }}</td>

                    <!-- Akhir -->
                    <td class="center">{{ $visit->usg_check ? 'Ya' : 'Tidak' }}</td>
                    <td class="center">{{ $visit->counseling_check ? 'V' : '' }}</td>
                    <td class="center">{{ $visit->risk_level }}</td>
                    <td class="center">{{ $visit->referral_target ? 'Ya' : 'Tidak' }}</td>
                    <td>{{ $visit->diagnosis }}</td>
                    <td>{{ $visit->follow_up }}</td>
                    <td>{{ $visit->midwife_name }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
