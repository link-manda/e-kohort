<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Laporan KB</title>
</head>

<body>
    <table style="border-collapse: collapse; width: 100%;">
        <!-- Title Row -->
        <thead>
            <tr>
                <th colspan="20"
                    style="border: 1px solid black; padding: 10px; text-align: center; font-size: 16px; font-weight: bold;">
                    REGISTER PELAYANAN KELUARGA BERENCANA (KB)
                    <br>
                    PERIODE: {{ $startDate }} s/d {{ $endDate }}
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
                    NO RM</th>
                <th rowspan="2"
                    style="border: 1px solid black; padding: 8px; text-align: center; vertical-align: middle; font-weight: bold; background-color: #f0f0f0;">
                    NO KK</th>

                <!-- PESERTA KB (Parent Header - Colspan 5) -->
                <th colspan="5"
                    style="border: 1px solid black; padding: 8px; text-align: center; vertical-align: middle; font-weight: bold; background-color: #ffc0cb;">
                    PESERTA KB</th>

                <!-- SUAMI (Parent Header - Colspan 2) -->
                <th colspan="2"
                    style="border: 1px solid black; padding: 8px; text-align: center; vertical-align: middle; font-weight: bold; background-color: #add8e6;">
                    SUAMI</th>

                <!-- PELAYANAN (Parent Header - Colspan 4) -->
                <th colspan="4"
                    style="border: 1px solid black; padding: 8px; text-align: center; vertical-align: middle; font-weight: bold; background-color: #ffe4b5;">
                    PELAYANAN</th>

                <!-- PEMERIKSAAN (Parent Header - Colspan 2) -->
                <th colspan="2"
                    style="border: 1px solid black; padding: 8px; text-align: center; vertical-align: middle; font-weight: bold; background-color: #98fb98;">
                    PEMERIKSAAN</th>

                <th rowspan="2"
                    style="border: 1px solid black; padding: 8px; text-align: center; vertical-align: middle; font-weight: bold; background-color: #f0f0f0;">
                    TGL KEMBALI</th>
                <th rowspan="2"
                    style="border: 1px solid black; padding: 8px; text-align: center; vertical-align: middle; font-weight: bold; background-color: #f0f0f0;">
                    DIAGNOSA /<br>CATATAN</th>
                <th rowspan="2"
                    style="border: 1px solid black; padding: 8px; text-align: center; vertical-align: middle; font-weight: bold; background-color: #f0f0f0;">
                    BIDAN</th>
            </tr>

            <!-- Child Headers (Row 2) -->
            <tr>
                <!-- PESERTA KB Child Headers -->
                <th
                    style="border: 1px solid black; padding: 6px; text-align: center; vertical-align: middle; font-weight: bold; background-color: #ffb6c1;">
                    NAMA</th>
                <th
                    style="border: 1px solid black; padding: 6px; text-align: center; vertical-align: middle; font-weight: bold; background-color: #ffb6c1;">
                    NIK</th>
                <th
                    style="border: 1px solid black; padding: 6px; text-align: center; vertical-align: middle; font-weight: bold; background-color: #ffb6c1;">
                    TTL</th>
                <th
                    style="border: 1px solid black; padding: 6px; text-align: center; vertical-align: middle; font-weight: bold; background-color: #ffb6c1;">
                    UMUR</th>
                <th
                    style="border: 1px solid black; padding: 6px; text-align: center; vertical-align: middle; font-weight: bold; background-color: #ffb6c1;">
                    ALAMAT</th>

                <!-- SUAMI Child Headers -->
                <th
                    style="border: 1px solid black; padding: 6px; text-align: center; vertical-align: middle; font-weight: bold; background-color: #87ceeb;">
                    NAMA</th>
                <th
                    style="border: 1px solid black; padding: 6px; text-align: center; vertical-align: middle; font-weight: bold; background-color: #87ceeb;">
                    NIK</th>

                <!-- PELAYANAN Child Headers -->
                <th
                    style="border: 1px solid black; padding: 6px; text-align: center; vertical-align: middle; font-weight: bold; background-color: #ffd699;">
                    JENIS</th>
                <th
                    style="border: 1px solid black; padding: 6px; text-align: center; vertical-align: middle; font-weight: bold; background-color: #ffd699;">
                    METODE</th>
                <th
                    style="border: 1px solid black; padding: 6px; text-align: center; vertical-align: middle; font-weight: bold; background-color: #ffd699;">
                    MEREK</th>
                <th
                    style="border: 1px solid black; padding: 6px; text-align: center; vertical-align: middle; font-weight: bold; background-color: #ffd699;">
                    BAYAR</th>

                <!-- PEMERIKSAAN Child Headers -->
                <th
                    style="border: 1px solid black; padding: 6px; text-align: center; vertical-align: middle; font-weight: bold; background-color: #c1ffc1;">
                    BB (Kg)</th>
                <th
                    style="border: 1px solid black; padding: 6px; text-align: center; vertical-align: middle; font-weight: bold; background-color: #c1ffc1;">
                    TD (mmHg)</th>
            </tr>
        </thead>

        <tbody>
            @forelse($visits as $index => $visit)
                @php
                    $patient = $visit->patient;

                    // Format TTL
                    $ttl =
                        ($patient->pob ?? '-') .
                        ', ' .
                        ($patient->dob ? \Carbon\Carbon::parse($patient->dob)->locale('id')->isoFormat('D MMM YYYY') : '-');

                    // Age
                    $age = $patient->dob ? \Carbon\Carbon::parse($patient->dob)->diffInYears(\Carbon\Carbon::parse($visit->visit_date)) : '-';

                    // TD
                    $td =
                        $visit->blood_pressure_systolic && $visit->blood_pressure_diastolic
                            ? $visit->blood_pressure_systolic . '/' . $visit->blood_pressure_diastolic
                            : '-';

                    // Note
                    $note = $visit->physical_exam_notes ?? ($visit->side_effects ?? ($visit->diagnosis ?? '-'));
                @endphp
                <tr>
                    <!-- NO -->
                    <td style="border: 1px solid black; padding: 5px; text-align: center; vertical-align: middle;">
                        {{ $index + 1 }}</td>

                    <!-- TANGGAL -->
                    <td style="border: 1px solid black; padding: 5px; text-align: center; vertical-align: middle;">
                        {{ $visit->visit_date->format('d/m/Y') }}</td>

                    <!-- NO RM -->
                    <td style="border: 1px solid black; padding: 5px; text-align: center; vertical-align: middle;">
                        {{ $visit->no_rm ?? '-' }}</td>

                    <!-- NO KK -->
                    <td style="border: 1px solid black; padding: 5px; text-align: center; vertical-align: middle;">
                        {{ $patient->no_kk ?? '-' }}</td>

                    <!-- NAMA IBU -->
                    <td style="border: 1px solid black; padding: 5px; vertical-align: middle;">{{ $patient->name }}</td>

                    <!-- NIK IBU -->
                    <td style="border: 1px solid black; padding: 5px; text-align: center; vertical-align: middle;">
                        {{ $patient->nik ?? '-' }}</td>

                    <!-- TTL IBU -->
                    <td style="border: 1px solid black; padding: 5px; vertical-align: middle;">{{ $ttl }}</td>

                    <!-- UMUR IBU -->
                    <td style="border: 1px solid black; padding: 5px; text-align: center; vertical-align: middle;">
                        {{ $age }} th</td>

                    <!-- ALAMAT -->
                    <td style="border: 1px solid black; padding: 5px; vertical-align: middle;">
                        {{ $patient->address ?? '-' }}</td>

                    <!-- NAMA SUAMI -->
                    <td style="border: 1px solid black; padding: 5px; vertical-align: middle;">
                        {{ $patient->husband_name ?? '-' }}</td>

                    <!-- NIK SUAMI -->
                    <td style="border: 1px solid black; padding: 5px; text-align: center; vertical-align: middle;">
                        {{ $patient->husband_nik ?? '-' }}</td>

                    <!-- JENIS KUNJUNGAN -->
                    <td style="border: 1px solid black; padding: 5px; text-align: center; vertical-align: middle;">
                        {{ $visit->visit_type }}</td>

                    <!-- METODE KB -->
                    <td style="border: 1px solid black; padding: 5px; text-align: center; vertical-align: middle;">
                        {{ $visit->kbMethod->name ?? '-' }}</td>

                    <!-- MEREK -->
                    <td style="border: 1px solid black; padding: 5px; text-align: center; vertical-align: middle;">
                        {{ $visit->contraception_brand ?? '-' }}</td>

                    <!-- PEMBAYARAN -->
                    <td style="border: 1px solid black; padding: 5px; text-align: center; vertical-align: middle;">
                        {{ $visit->payment_type }}</td>

                    <!-- BB -->
                    <td style="border: 1px solid black; padding: 5px; text-align: center; vertical-align: middle;">
                        {{ $visit->weight ?? '-' }}</td>

                    <!-- TD -->
                    <td style="border: 1px solid black; padding: 5px; text-align: center; vertical-align: middle;">
                        {{ $td }}</td>

                    <!-- TGL KEMBALI -->
                    <td style="border: 1px solid black; padding: 5px; text-align: center; vertical-align: middle;">
                        {{ $visit->next_visit_date ? \Carbon\Carbon::parse($visit->next_visit_date)->format('d/m/Y') : '-' }}
                    </td>

                    <!-- DIAGNOSA / CATATAN -->
                    <td style="border: 1px solid black; padding: 5px; vertical-align: middle;">{{ $note }}
                    </td>

                    <!-- BIDAN -->
                    <td style="border: 1px solid black; padding: 5px; vertical-align: middle;">
                        {{ $visit->midwife_name ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="20"
                        style="border: 1px solid black; padding: 20px; text-align: center; vertical-align: middle; font-style: italic;">
                        Tidak ada data kunjungan KB pada periode ini.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>
