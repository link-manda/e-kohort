<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Reg. ANC</title>
</head>

<body>
    <table style="border-collapse: collapse; width: 100%;">
        <!-- Title Row -->
        <thead>
            <tr>
                <th colspan="47"
                    style="border: 1px solid black; padding: 10px; text-align: center; font-size: 16px; font-weight: bold;">
                    REGISTER ANTENATAL CARE (ANC) TERINTEGRASI
                    <br>
                    PERIODE: {{ strtoupper($period) }}
                </th>
            </tr>

            <!-- Parent Headers (Row 1) -->
            <tr>
                <th rowspan="2"
                    style="border: 1px solid black; padding: 8px; text-align: center; vertical-align: middle; font-weight: bold; background-color: #f0f0f0;">
                    NO</th>
                <th rowspan="2"
                    style="border: 1px solid black; padding: 8px; text-align: center; vertical-align: middle; font-weight: bold; background-color: #f0f0f0;">
                    TANGGAL</th>
                <th rowspan="2"
                    style="border: 1px solid black; padding: 8px; text-align: center; vertical-align: middle; font-weight: bold; background-color: #f0f0f0;">
                    NO RM / KK / BPJS</th>

                <!-- IDENTITAS (Parent Header - Colspan 7) -->
                <th colspan="7"
                    style="border: 1px solid black; padding: 8px; text-align: center; vertical-align: middle; font-weight: bold; background-color: #ffc0cb;">
                    IDENTITAS</th>

                <th rowspan="2"
                    style="border: 1px solid black; padding: 8px; text-align: center; vertical-align: middle; font-weight: bold; background-color: #f0f0f0;">
                    ALAMAT DOMISILI</th>

                <!-- USIA KEHAMILAN (Parent Header - Colspan 2) -->
                <th colspan="2"
                    style="border: 1px solid black; padding: 8px; text-align: center; vertical-align: middle; font-weight: bold; background-color: #add8e6;">
                    USIA KEHAMILAN</th>

                <!-- RIWAYAT (Parent Header - Colspan 7) -->
                <th colspan="7"
                    style="border: 1px solid black; padding: 8px; text-align: center; vertical-align: middle; font-weight: bold; background-color: #ffe4b5;">
                    RIWAYAT & KUNJUNGAN</th>

                <!-- 12 T (Parent Header - Colspan 12) -->
                <th colspan="12"
                    style="border: 1px solid black; padding: 8px; text-align: center; vertical-align: middle; font-weight: bold; background-color: #98fb98;">
                    PEMERIKSAAN (10 T)</th>

                <!-- LAB (Parent Header - Colspan 7) -->
                <th colspan="7"
                    style="border: 1px solid black; padding: 8px; text-align: center; vertical-align: middle; font-weight: bold; background-color: #e6e6fa;">
                    LABORATORIUM & ANALISA</th>

                <!-- TINDAKAN (Parent Header - Colspan 3) -->
                <th colspan="3"
                    style="border: 1px solid black; padding: 8px; text-align: center; vertical-align: middle; font-weight: bold; background-color: #f0f0f0;">
                    TINDAKAN</th>
            </tr>

            <!-- Child Headers (Row 2) -->
            <tr>
                <!-- IDENTITAS Child Headers -->
                <th
                    style="border: 1px solid black; padding: 6px; text-align: center; vertical-align: middle; font-weight: bold; background-color: #ffb6c1;">
                    NAMA IBU / SUAMI</th>
                <th
                    style="border: 1px solid black; padding: 6px; text-align: center; vertical-align: middle; font-weight: bold; background-color: #ffb6c1;">
                    NIK IBU / SUAMI</th>
                <th
                    style="border: 1px solid black; padding: 6px; text-align: center; vertical-align: middle; font-weight: bold; background-color: #ffb6c1;">
                    PEKERJAAN</th>
                <th
                    style="border: 1px solid black; padding: 6px; text-align: center; vertical-align: middle; font-weight: bold; background-color: #ffb6c1;">
                    PENDIDIKAN</th>
                <th
                    style="border: 1px solid black; padding: 6px; text-align: center; vertical-align: middle; font-weight: bold; background-color: #ffb6c1;">
                    UMUR / TTL</th>
                <th
                    style="border: 1px solid black; padding: 6px; text-align: center; vertical-align: middle; font-weight: bold; background-color: #ffb6c1;">
                    HP</th>
                <th
                    style="border: 1px solid black; padding: 6px; text-align: center; vertical-align: middle; font-weight: bold; background-color: #ffb6c1;">
                    GOL. DARAH</th>

                <!-- USIA KEHAMILAN Child Headers -->
                <th
                    style="border: 1px solid black; padding: 6px; text-align: center; vertical-align: middle; font-weight: bold; background-color: #87ceeb;">
                    GRAVIDA</th>
                <th
                    style="border: 1px solid black; padding: 6px; text-align: center; vertical-align: middle; font-weight: bold; background-color: #87ceeb;">
                    HPHT</th>

                <!-- RIWAYAT Child Headers -->
                <th
                    style="border: 1px solid black; padding: 6px; text-align: center; vertical-align: middle; font-weight: bold; background-color: #ffd699;">
                    K1</th>
                <th
                    style="border: 1px solid black; padding: 6px; text-align: center; vertical-align: middle; font-weight: bold; background-color: #ffd699;">
                    K2</th>
                <th
                    style="border: 1px solid black; padding: 6px; text-align: center; vertical-align: middle; font-weight: bold; background-color: #ffd699;">
                    K3</th>
                <th
                    style="border: 1px solid black; padding: 6px; text-align: center; vertical-align: middle; font-weight: bold; background-color: #ffd699;">
                    K4</th>
                <th
                    style="border: 1px solid black; padding: 6px; text-align: center; vertical-align: middle; font-weight: bold; background-color: #ffd699;">
                    K5</th>
                <th
                    style="border: 1px solid black; padding: 6px; text-align: center; vertical-align: middle; font-weight: bold; background-color: #ffd699;">
                    K6</th>
                <th
                    style="border: 1px solid black; padding: 6px; text-align: center; vertical-align: middle; font-weight: bold; background-color: #ffd699;">
                    K8</th>

                <!-- 12 T Child Headers -->
                <th
                    style="border: 1px solid black; padding: 6px; text-align: center; vertical-align: middle; font-weight: bold; background-color: #c1ffc1;">
                    ANC 12T</th>
                <th
                    style="border: 1px solid black; padding: 6px; text-align: center; vertical-align: middle; font-weight: bold; background-color: #c1ffc1;">
                    UK (Mg)</th>
                <th
                    style="border: 1px solid black; padding: 6px; text-align: center; vertical-align: middle; font-weight: bold; background-color: #c1ffc1;">
                    BB (kg)</th>
                <th
                    style="border: 1px solid black; padding: 6px; text-align: center; vertical-align: middle; font-weight: bold; background-color: #c1ffc1;">
                    TB (cm)</th>
                <th
                    style="border: 1px solid black; padding: 6px; text-align: center; vertical-align: middle; font-weight: bold; background-color: #c1ffc1;">
                    IMT</th>
                <th
                    style="border: 1px solid black; padding: 6px; text-align: center; vertical-align: middle; font-weight: bold; background-color: #c1ffc1;">
                    LILA (cm)</th>
                <th
                    style="border: 1px solid black; padding: 6px; text-align: center; vertical-align: middle; font-weight: bold; background-color: #c1ffc1;">
                    KEK</th>
                <th
                    style="border: 1px solid black; padding: 6px; text-align: center; vertical-align: middle; font-weight: bold; background-color: #c1ffc1;">
                    NORMAL</th>
                <th
                    style="border: 1px solid black; padding: 6px; text-align: center; vertical-align: middle; font-weight: bold; background-color: #c1ffc1;">
                    TD (mmHg)</th>
                <th
                    style="border: 1px solid black; padding: 6px; text-align: center; vertical-align: middle; font-weight: bold; background-color: #c1ffc1;">
                    TFU (cm)</th>
                <th
                    style="border: 1px solid black; padding: 6px; text-align: center; vertical-align: middle; font-weight: bold; background-color: #c1ffc1;">
                    DJJ</th>
                <th
                    style="border: 1px solid black; padding: 6px; text-align: center; vertical-align: middle; font-weight: bold; background-color: #c1ffc1;">
                    MAP</th>

                <!-- LAB Child Headers -->
                <th
                    style="border: 1px solid black; padding: 6px; text-align: center; vertical-align: middle; font-weight: bold; background-color: #d8bfd8;">
                    IMUN TT</th>
                <th
                    style="border: 1px solid black; padding: 6px; text-align: center; vertical-align: middle; font-weight: bold; background-color: #d8bfd8;">
                    TAB FE</th>
                <th
                    style="border: 1px solid black; padding: 6px; text-align: center; vertical-align: middle; font-weight: bold; background-color: #d8bfd8;">
                    HIV</th>
                <th
                    style="border: 1px solid black; padding: 6px; text-align: center; vertical-align: middle; font-weight: bold; background-color: #d8bfd8;">
                    SIFILIS</th>
                <th
                    style="border: 1px solid black; padding: 6px; text-align: center; vertical-align: middle; font-weight: bold; background-color: #d8bfd8;">
                    HBsAg</th>
                <th
                    style="border: 1px solid black; padding: 6px; text-align: center; vertical-align: middle; font-weight: bold; background-color: #d8bfd8;">
                    HB (gr/dL)</th>
                <th
                    style="border: 1px solid black; padding: 6px; text-align: center; vertical-align: middle; font-weight: bold; background-color: #d8bfd8;">
                    Prot. Urine</th>

                <!-- TINDAKAN Child Headers -->
                <th
                    style="border: 1px solid black; padding: 6px; text-align: center; vertical-align: middle; font-weight: bold; background-color: #f0f0f0;">
                    RESIKO</th>
                <th
                    style="border: 1px solid black; padding: 6px; text-align: center; vertical-align: middle; font-weight: bold; background-color: #f0f0f0;">
                    DIAGNOSA</th>
                <th
                    style="border: 1px solid black; padding: 6px; text-align: center; vertical-align: middle; font-weight: bold; background-color: #f0f0f0;">
                    NAMA NAKES</th>
            </tr>
        </thead>

        <tbody>
            @forelse($visits as $index => $visit)
                @php
                    $patient = $visit->pregnancy->patient;
                    $preg = $visit->pregnancy;

                    // Ids
                    $ids = ($patient->no_rm ?? '-') . '<br>' . ($patient->no_kk ?? '-') . '<br>' . ($patient->no_bpjs ?? '-');

                    // Names
                    $names = ($patient->name ?? '-') . '<br>' . ($patient->husband_name ?? '-');

                    // NIKs
                    $niks = ($patient->nik ? "'".$patient->nik : '-') . '<br>' . ($patient->husband_nik ? "'".$patient->husband_nik : '-');

                    // Job
                    $jobs = ($patient->job ?? '-') . '<br>' . ($patient->husband_job ?? '-');

                    // Edu
                    $edus = ($patient->education ?? '-') . '<br>' . ($patient->husband_education ?? '-');

                    // Age / TTL
                    $age = $patient->dob ? \Carbon\Carbon::parse($patient->dob)->age . ' Thn' : '-';
                    $ttl = ($patient->pob ?? '-') . ', ' . ($patient->dob ? \Carbon\Carbon::parse($patient->dob)->format('d-m-Y') : '-');
                    $ageTtl = $age . '<br>' . $ttl;

                    // Gol Darah
                    $bloods = ($patient->blood_type ?? '-') . '<br>' . ($patient->husband_blood_type ?? '-');

                    // KEK Check
                    $isKek = ($visit->lila && $visit->lila < 23.5) ? '✔' : '-';
                    $isNormal = ($visit->lila && $visit->lila >= 23.5) ? '✔' : '-';

                    // TD
                    $td = ($visit->systolic ?? '-') . '/' . ($visit->diastolic ?? '-');

                @endphp
                <tr>
                    <!-- NO -->
                    <td style="border: 1px solid black; padding: 5px; text-align: center; vertical-align: middle;">
                        {{ $index + 1 }}</td>

                    <!-- TANGGAL -->
                    <td style="border: 1px solid black; padding: 5px; text-align: center; vertical-align: middle;">
                        {{ $visit->visit_date ? $visit->visit_date->format('d/m/Y') : '-' }}</td>

                    <!-- NO RM/KK/BPJS -->
                    <td style="border: 1px solid black; padding: 5px; text-align: center; vertical-align: middle;">
                        {!! $ids !!}</td>

                    <!-- NAMA -->
                    <td style="border: 1px solid black; padding: 5px; vertical-align: middle;">{!! $names !!}</td>

                    <!-- NIK -->
                    <td style="border: 1px solid black; padding: 5px; text-align: center; vertical-align: middle;">
                        {!! $niks !!}</td>

                    <!-- PEKERJAAN -->
                    <td style="border: 1px solid black; padding: 5px; text-align: center; vertical-align: middle;">
                        {!! $jobs !!}</td>

                    <!-- PENDIDIKAN -->
                    <td style="border: 1px solid black; padding: 5px; text-align: center; vertical-align: middle;">
                        {!! $edus !!}</td>

                    <!-- UMUR/TTL -->
                    <td style="border: 1px solid black; padding: 5px; text-align: center; vertical-align: middle;">
                        {!! $ageTtl !!}</td>

                    <!-- HP -->
                    <td style="border: 1px solid black; padding: 5px; text-align: center; vertical-align: middle;">
                        {{ $patient->phone ? "'".$patient->phone : '-' }}</td>

                    <!-- GOL DARAH -->
                    <td style="border: 1px solid black; padding: 5px; text-align: center; vertical-align: middle;">
                        {!! $bloods !!}</td>

                    <!-- ALAMAT -->
                    <td style="border: 1px solid black; padding: 5px; vertical-align: middle;">
                        {{ $patient->address ?? '-' }}</td>

                    <!-- GRAVIDA -->
                    <td style="border: 1px solid black; padding: 5px; text-align: center; vertical-align: middle;">
                        {{ $preg->gestational_age ?? '-' }}</td>

                    <!-- HPHT -->
                    <td style="border: 1px solid black; padding: 5px; text-align: center; vertical-align: middle;">
                        {{ $preg->pregnancy_gap ?? '-' }}</td>

                    <!-- K1-K8 -->
                    <td style="border: 1px solid black; padding: 5px; text-align: center; vertical-align: middle;">
                        {{ $visit->visit_code == 'K1' ? '✔' : '' }}</td>
                    <td style="border: 1px solid black; padding: 5px; text-align: center; vertical-align: middle;">
                        {{ $visit->visit_code == 'K2' ? '✔' : '' }}</td>
                    <td style="border: 1px solid black; padding: 5px; text-align: center; vertical-align: middle;">
                        {{ $visit->visit_code == 'K3' ? '✔' : '' }}</td>
                    <td style="border: 1px solid black; padding: 5px; text-align: center; vertical-align: middle;">
                        {{ $visit->visit_code == 'K4' ? '✔' : '' }}</td>
                    <td style="border: 1px solid black; padding: 5px; text-align: center; vertical-align: middle;">
                        {{ $visit->visit_code == 'K5' ? '✔' : '' }}</td>
                    <td style="border: 1px solid black; padding: 5px; text-align: center; vertical-align: middle;">
                        {{ $visit->visit_code == 'K6' ? '✔' : '' }}</td>
                    <td style="border: 1px solid black; padding: 5px; text-align: center; vertical-align: middle;">
                        {{ $visit->visit_code == 'K8' ? '✔' : '' }}</td>

                    <!-- ANC 12T -->
                    <td style="border: 1px solid black; padding: 5px; text-align: center; vertical-align: middle;">
                        {{ $visit->anc_12t ? '✔' : '' }}</td>

                    <!-- UK -->
                    <td style="border: 1px solid black; padding: 5px; text-align: center; vertical-align: middle;">
                        {{ $preg->weight_before ?? '-' }}</td>

                    <!-- BB -->
                    <td style="border: 1px solid black; padding: 5px; text-align: center; vertical-align: middle;">
                        {{ $visit->weight ?? '-' }}</td>

                    <!-- TB -->
                    <td style="border: 1px solid black; padding: 5px; text-align: center; vertical-align: middle;">
                        {{ $visit->height ?? '-' }}</td>

                    <!-- IMT -->
                    <td style="border: 1px solid black; padding: 5px; text-align: center; vertical-align: middle;">
                        {{ $visit->bmi ?? '-' }}</td>

                    <!-- LILA -->
                    <td style="border: 1px solid black; padding: 5px; text-align: center; vertical-align: middle;">
                        {{ $visit->lila ?? '-' }}</td>

                    <!-- KEK -->
                    <td style="border: 1px solid black; padding: 5px; text-align: center; vertical-align: middle;">
                        {{ $isKek }}</td>

                    <!-- NORMAL -->
                    <td style="border: 1px solid black; padding: 5px; text-align: center; vertical-align: middle;">
                        {{ $isNormal }}</td>

                    <!-- TD -->
                    <td style="border: 1px solid black; padding: 5px; text-align: center; vertical-align: middle;">
                        {{ $td }}</td>

                    <!-- TFU -->
                    <td style="border: 1px solid black; padding: 5px; text-align: center; vertical-align: middle;">
                        {{ $visit->tfu ?? '-' }}</td>

                    <!-- DJJ -->
                    <td style="border: 1px solid black; padding: 5px; text-align: center; vertical-align: middle;">
                        {{ $visit->djj ?? '-' }}</td>

                    <!-- MAP -->
                    <td style="border: 1px solid black; padding: 5px; text-align: center; vertical-align: middle;">
                        {{ $visit->fetal_presentation ?? '-' }}</td>

                    <!-- IMUN TT -->
                    <td style="border: 1px solid black; padding: 5px; text-align: center; vertical-align: middle;">
                        {{ $visit->tt_immunization ?? '-' }}</td>

                    <!-- TAB FE -->
                    <td style="border: 1px solid black; padding: 5px; text-align: center; vertical-align: middle;">
                        {{ $visit->fe_tablets ?? '-' }}</td>

                    <!-- HIV -->
                    <td style="border: 1px solid black; padding: 5px; text-align: center; vertical-align: middle;">
                        {{ $visit->hiv_status ?? '-' }}</td>

                    <!-- SIFILIS -->
                    <td style="border: 1px solid black; padding: 5px; text-align: center; vertical-align: middle;">
                        {{ $visit->syphilis_status ?? '-' }}</td>

                    <!-- HBsAg -->
                    <td style="border: 1px solid black; padding: 5px; text-align: center; vertical-align: middle;">
                        {{ $visit->hbsag_status ?? '-' }}</td>

                    <!-- HB -->
                    <td style="border: 1px solid black; padding: 5px; text-align: center; vertical-align: middle;">
                        {{ $visit->hb ?? '-' }}</td>

                    <!-- Prot Urine -->
                    <td style="border: 1px solid black; padding: 5px; text-align: center; vertical-align: middle;">
                        {{ $visit->protein_urine ?? '-' }}</td>

                    <!-- RESIKO -->
                    <td style="border: 1px solid black; padding: 5px; text-align: center; vertical-align: middle;">
                        {{ $visit->risk_level ?? '-' }}</td>

                    <!-- DIAGNOSA -->
                    <td style="border: 1px solid black; padding: 5px; vertical-align: middle;">
                        {{ $visit->diagnosis ?? '-' }}</td>

                    <!-- NAKES -->
                    <td style="border: 1px solid black; padding: 5px; vertical-align: middle;">
                        {{ $visit->midwife_name ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="47"
                        style="border: 1px solid black; padding: 20px; text-align: center; vertical-align: middle; font-style: italic;">
                        Tidak ada data kunjungan ANC pada periode ini.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>
