<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Reg. ANC</title>
</head>
<body>
    <table style="border-collapse: collapse; width: 100%;">
        <thead>
            <tr>
                <th colspan="47" style="border: 1px solid black; padding: 10px; text-align: center; font-size: 16px; font-weight: bold;">
                    REGISTER ANTENATAL CARE (ANC) TERINTEGRASI
                    <br>
                    PERIODE: {{ strtoupper($period) }}
                </th>
            </tr>
            <tr>
                <th style="border: 1px solid black; background-color: #f0f0f0;">NO</th>
                <th style="border: 1px solid black; background-color: #f0f0f0;">TANGGAL</th>
                <th style="border: 1px solid black; background-color: #f0f0f0;">NO RM / KK / BPJS</th>

                <th style="border: 1px solid black; background-color: #ffc0cb;">NAMA IBU / SUAMI</th>
                <th style="border: 1px solid black; background-color: #ffc0cb;">NIK IBU / SUAMI</th>
                <th style="border: 1px solid black; background-color: #ffc0cb;">PEKERJAAN</th>
                <th style="border: 1px solid black; background-color: #ffc0cb;">PENDIDIKAN</th>
                <th style="border: 1px solid black; background-color: #ffc0cb;">UMUR / TTL</th>
                <th style="border: 1px solid black; background-color: #ffc0cb;">HP</th>
                <th style="border: 1px solid black; background-color: #ffc0cb;">GOL. DARAH</th>
                <th style="border: 1px solid black; background-color: #f0f0f0;">ALAMAT DOMISILI</th>

                <th style="border: 1px solid black; background-color: #87ceeb;">GRAVIDA</th>
                <th style="border: 1px solid black; background-color: #87ceeb;">HPHT</th>

                <th style="border: 1px solid black; background-color: #ffd699;">K1</th>
                <th style="border: 1px solid black; background-color: #ffd699;">K2</th>
                <th style="border: 1px solid black; background-color: #ffd699;">K3</th>
                <th style="border: 1px solid black; background-color: #ffd699;">K4</th>
                <th style="border: 1px solid black; background-color: #ffd699;">K5</th>
                <th style="border: 1px solid black; background-color: #ffd699;">K6</th>
                <th style="border: 1px solid black; background-color: #ffd699;">K8</th>

                <th style="border: 1px solid black; background-color: #c1ffc1;">ANC 12T</th>
                <th style="border: 1px solid black; background-color: #c1ffc1;">UK (Mg)</th>
                <th style="border: 1px solid black; background-color: #c1ffc1;">BB (kg)</th>
                <th style="border: 1px solid black; background-color: #c1ffc1;">TB (cm)</th>
                <th style="border: 1px solid black; background-color: #c1ffc1;">IMT</th>
                <th style="border: 1px solid black; background-color: #c1ffc1;">LILA (cm)</th>
                <th style="border: 1px solid black; background-color: #c1ffc1;">KEK</th>
                <th style="border: 1px solid black; background-color: #c1ffc1;">NORMAL</th>
                <th style="border: 1px solid black; background-color: #c1ffc1;">TD (mmHg)</th>
                <th style="border: 1px solid black; background-color: #c1ffc1;">TFU (cm)</th>
                <th style="border: 1px solid black; background-color: #c1ffc1;">DJJ</th>
                <th style="border: 1px solid black; background-color: #c1ffc1;">MAP</th>

                <th style="border: 1px solid black; background-color: #d8bfd8;">IMUN TT</th>
                <th style="border: 1px solid black; background-color: #d8bfd8;">TAB FE</th>
                <th style="border: 1px solid black; background-color: #d8bfd8;">HIV</th>
                <th style="border: 1px solid black; background-color: #d8bfd8;">SIFILIS</th>
                <th style="border: 1px solid black; background-color: #d8bfd8;">HBsAg</th>
                <th style="border: 1px solid black; background-color: #d8bfd8;">HB (gr/dL)</th>
                <th style="border: 1px solid black; background-color: #d8bfd8;">Prot. Urine</th>

                <th style="border: 1px solid black; background-color: #f0f0f0;">RESIKO</th>
                <th style="border: 1px solid black; background-color: #f0f0f0;">DIAGNOSA</th>
                <th style="border: 1px solid black; background-color: #f0f0f0;">NAMA NAKES</th>
            </tr>
        </thead>
        <tbody>
            @forelse($visits as $index => $visit)
                @php
                    $patient = $visit->pregnancy->patient;
                    $preg = $visit->pregnancy;
                @endphp
                <tr>
                    <td style="border: 1px solid black; padding: 5px; text-align: center; vertical-align: middle;">{{ $index + 1 }}</td>
                    <td style="border: 1px solid black; padding: 5px; text-align: center; vertical-align: middle;">{{ $visit->visit_date ? $visit->visit_date->format('d/m/Y') : '-' }}</td>
                    <td style="border: 1px solid black; padding: 5px; text-align: center; vertical-align: middle;">{{ $patient->no_rm ?? '-' }}<br>{{ $patient->no_kk ?? '-' }}<br>{{ $patient->no_bpjs ?? '-' }}</td>
                    <td style="border: 1px solid black; padding: 5px; vertical-align: middle;">{{ $patient->name ?? '-' }}<br>{{ $patient->husband_name ?? '-' }}</td>
                    <td style="border: 1px solid black; padding: 5px; text-align: center; vertical-align: middle;">{{ $patient->nik ? "'".$patient->nik : '-' }}<br>{{ $patient->husband_nik ? "'".$patient->husband_nik : '-' }}</td>
                    <td style="border: 1px solid black; padding: 5px; text-align: center; vertical-align: middle;">{{ $patient->job ?? '-' }}<br>{{ $patient->husband_job ?? '-' }}</td>
                    <td style="border: 1px solid black; padding: 5px; text-align: center; vertical-align: middle;">{{ $patient->education ?? '-' }}<br>{{ $patient->husband_education ?? '-' }}</td>
                    <td style="border: 1px solid black; padding: 5px; text-align: center; vertical-align: middle;">{{ $patient->dob ? $patient->dob->age : '-' }} Thn<br>{{ $patient->pob ?? '-' }}, {{ $patient->dob ? $patient->dob->format('d-m-Y') : '-' }}</td>
                    <td style="border: 1px solid black; padding: 5px; text-align: center; vertical-align: middle;">{{ $patient->phone ? "'".$patient->phone : '-' }}</td>
                    <td style="border: 1px solid black; padding: 5px; text-align: center; vertical-align: middle;">{{ $patient->blood_type ?? '-' }}<br>{{ $patient->husband_blood_type ?? '-' }}</td>
                    <td style="border: 1px solid black; padding: 5px; vertical-align: middle;">{{ $patient->address ?? '-' }}</td>
                    <td style="border: 1px solid black; padding: 5px; text-align: center; vertical-align: middle;">{{ $preg->gestational_age ?? '-' }}</td>
                    <td style="border: 1px solid black; padding: 5px; text-align: center; vertical-align: middle;">{{ $preg->pregnancy_gap ?? '-' }}</td>
                    <td style="border: 1px solid black; padding: 5px; text-align: center; vertical-align: middle;">{{ $visit->visit_code == 'K1' ? '✔' : '' }}</td>
                    <td style="border: 1px solid black; padding: 5px; text-align: center; vertical-align: middle;">{{ $visit->visit_code == 'K2' ? '✔' : '' }}</td>
                    <td style="border: 1px solid black; padding: 5px; text-align: center; vertical-align: middle;">{{ $visit->visit_code == 'K3' ? '✔' : '' }}</td>
                    <td style="border: 1px solid black; padding: 5px; text-align: center; vertical-align: middle;">{{ $visit->visit_code == 'K4' ? '✔' : '' }}</td>
                    <td style="border: 1px solid black; padding: 5px; text-align: center; vertical-align: middle;">{{ $visit->visit_code == 'K5' ? '✔' : '' }}</td>
                    <td style="border: 1px solid black; padding: 5px; text-align: center; vertical-align: middle;">{{ $visit->visit_code == 'K6' ? '✔' : '' }}</td>
                    <td style="border: 1px solid black; padding: 5px; text-align: center; vertical-align: middle;">{{ $visit->visit_code == 'K8' ? '✔' : '' }}</td>
                    <td style="border: 1px solid black; padding: 5px; text-align: center; vertical-align: middle;">{{ $visit->anc_12t ? '✔' : '' }}</td>
                    <td style="border: 1px solid black; padding: 5px; text-align: center; vertical-align: middle;">{{ $preg->weight_before ?? '-' }}</td>
                    <td style="border: 1px solid black; padding: 5px; text-align: center; vertical-align: middle;">{{ $visit->weight ?? '-' }}</td>
                    <td style="border: 1px solid black; padding: 5px; text-align: center; vertical-align: middle;">{{ $visit->height ?? '-' }}</td>
                    <td style="border: 1px solid black; padding: 5px; text-align: center; vertical-align: middle;">{{ $visit->bmi ?? '-' }}</td>
                    <td style="border: 1px solid black; padding: 5px; text-align: center; vertical-align: middle;">{{ $visit->lila ?? '-' }}</td>
                    <td style="border: 1px solid black; padding: 5px; text-align: center; vertical-align: middle;">{{ ($visit->lila && $visit->lila < 23.5) ? '✔' : '-' }}</td>
                    <td style="border: 1px solid black; padding: 5px; text-align: center; vertical-align: middle;">{{ ($visit->lila && $visit->lila >= 23.5) ? '✔' : '-' }}</td>
                    <td style="border: 1px solid black; padding: 5px; text-align: center; vertical-align: middle;">{{ ($visit->systolic ?? '-') . '/' . ($visit->diastolic ?? '-') }}</td>
                    <td style="border: 1px solid black; padding: 5px; text-align: center; vertical-align: middle;">{{ $visit->tfu ?? '-' }}</td>
                    <td style="border: 1px solid black; padding: 5px; text-align: center; vertical-align: middle;">{{ $visit->djj ?? '-' }}</td>
                    <td style="border: 1px solid black; padding: 5px; text-align: center; vertical-align: middle;">{{ $visit->fetal_presentation ?? '-' }}</td>
                    <td style="border: 1px solid black; padding: 5px; text-align: center; vertical-align: middle;">{{ $visit->tt_immunization ?? '-' }}</td>
                    <td style="border: 1px solid black; padding: 5px; text-align: center; vertical-align: middle;">{{ $visit->fe_tablets ?? '-' }}</td>
                    <td style="border: 1px solid black; padding: 5px; text-align: center; vertical-align: middle;">{{ $visit->hiv_status ?? '-' }}</td>
                    <td style="border: 1px solid black; padding: 5px; text-align: center; vertical-align: middle;">{{ $visit->syphilis_status ?? '-' }}</td>
                    <td style="border: 1px solid black; padding: 5px; text-align: center; vertical-align: middle;">{{ $visit->hbsag_status ?? '-' }}</td>
                    <td style="border: 1px solid black; padding: 5px; text-align: center; vertical-align: middle;">{{ $visit->hb ?? '-' }}</td>
                    <td style="border: 1px solid black; padding: 5px; text-align: center; vertical-align: middle;">{{ $visit->protein_urine ?? '-' }}</td>
                    <td style="border: 1px solid black; padding: 5px; text-align: center; vertical-align: middle;">{{ $visit->risk_level ?? '-' }}</td>
                    <td style="border: 1px solid black; padding: 5px; vertical-align: middle;">{{ $visit->diagnosis ?? '-' }}</td>
                    <td style="border: 1px solid black; padding: 5px; vertical-align: middle;">{{ $visit->midwife_name ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="47" style="border: 1px solid black; padding: 20px; text-align: center; vertical-align: middle; font-style: italic;">
                        Tidak ada data kunjungan ANC pada periode ini.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
