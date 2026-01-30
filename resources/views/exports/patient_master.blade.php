<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Data Pasien</title>
</head>

<body>
    <table style="border-collapse: collapse; width: 100%;">
        <!-- Title Row -->
        <thead>
            <tr>
                <th colspan="21"
                    style="border: 1px solid black; padding: 10px; text-align: center; font-size: 16px; font-weight: bold;">
                    DATA PASIEN TERDAFTAR
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
                    NO RM</th>
                <th rowspan="2"
                    style="border: 1px solid black; padding: 8px; text-align: center; vertical-align: middle; font-weight: bold; background-color: #f0f0f0;">
                    NIK</th>
                <th rowspan="2"
                    style="border: 1px solid black; padding: 8px; text-align: center; vertical-align: middle; font-weight: bold; background-color: #f0f0f0;">
                    NO KK</th>
                <th rowspan="2"
                    style="border: 1px solid black; padding: 8px; text-align: center; vertical-align: middle; font-weight: bold; background-color: #f0f0f0;">
                    NO BPJS</th>

                <!-- IDENTITAS PASIEN (Parent Header - Colspan 7) -->
                <th colspan="7"
                    style="border: 1px solid black; padding: 8px; text-align: center; vertical-align: middle; font-weight: bold; background-color: #ffc0cb;">
                    IDENTITAS PASIEN</th>

                <!-- ALAMAT & KONTAK (Parent Header - Colspan 2) -->
                <th colspan="2"
                    style="border: 1px solid black; padding: 8px; text-align: center; vertical-align: middle; font-weight: bold; background-color: #ffe4b5;">
                    ALAMAT & KONTAK</th>

                <!-- DATA SUAMI (Parent Header - Colspan 5) -->
                <th colspan="5"
                    style="border: 1px solid black; padding: 8px; text-align: center; vertical-align: middle; font-weight: bold; background-color: #add8e6;">
                    DATA SUAMI</th>

                <!-- STATUS (Parent Header - Colspan 2) -->
                <th colspan="2"
                    style="border: 1px solid black; padding: 8px; text-align: center; vertical-align: middle; font-weight: bold; background-color: #98fb98;">
                    STATUS</th>
            </tr>

            <!-- Child Headers (Row 2) -->
            <tr>
                <!-- IDENTITAS Child Headers -->
                <th
                    style="border: 1px solid black; padding: 6px; text-align: center; vertical-align: middle; font-weight: bold; background-color: #ffb6c1;">
                    NAMA LENGKAP</th>
                <th
                    style="border: 1px solid black; padding: 6px; text-align: center; vertical-align: middle; font-weight: bold; background-color: #ffb6c1;">
                    TEMPAT LAHIR</th>
                <th
                    style="border: 1px solid black; padding: 6px; text-align: center; vertical-align: middle; font-weight: bold; background-color: #ffb6c1;">
                    TGL LAHIR</th>
                <th
                    style="border: 1px solid black; padding: 6px; text-align: center; vertical-align: middle; font-weight: bold; background-color: #ffb6c1;">
                    UMUR (THN)</th>
                <th
                    style="border: 1px solid black; padding: 6px; text-align: center; vertical-align: middle; font-weight: bold; background-color: #ffb6c1;">
                    PENDIDIKAN</th>
                <th
                    style="border: 1px solid black; padding: 6px; text-align: center; vertical-align: middle; font-weight: bold; background-color: #ffb6c1;">
                    PEKERJAAN</th>
                <th
                    style="border: 1px solid black; padding: 6px; text-align: center; vertical-align: middle; font-weight: bold; background-color: #ffb6c1;">
                    GOL. DARAH</th>

                <!-- ALAMAT Child Headers -->
                <th
                    style="border: 1px solid black; padding: 6px; text-align: center; vertical-align: middle; font-weight: bold; background-color: #ffd699;">
                    ALAMAT LENGKAP</th>
                <th
                    style="border: 1px solid black; padding: 6px; text-align: center; vertical-align: middle; font-weight: bold; background-color: #ffd699;">
                    NO HP</th>

                <!-- SUAMI Child Headers -->
                <th
                    style="border: 1px solid black; padding: 6px; text-align: center; vertical-align: middle; font-weight: bold; background-color: #87ceeb;">
                    NAMA SUAMI</th>
                <th
                    style="border: 1px solid black; padding: 6px; text-align: center; vertical-align: middle; font-weight: bold; background-color: #87ceeb;">
                    NIK SUAMI</th>
                <th
                    style="border: 1px solid black; padding: 6px; text-align: center; vertical-align: middle; font-weight: bold; background-color: #87ceeb;">
                    PENDIDIKAN</th>
                <th
                    style="border: 1px solid black; padding: 6px; text-align: center; vertical-align: middle; font-weight: bold; background-color: #87ceeb;">
                    PEKERJAAN</th>
                <th
                    style="border: 1px solid black; padding: 6px; text-align: center; vertical-align: middle; font-weight: bold; background-color: #87ceeb;">
                    GOL. DARAH</th>

                <!-- STATUS Child Headers -->
                <th
                    style="border: 1px solid black; padding: 6px; text-align: center; vertical-align: middle; font-weight: bold; background-color: #c1ffc1;">
                    STATUS KEHAMILAN</th>
                <th
                    style="border: 1px solid black; padding: 6px; text-align: center; vertical-align: middle; font-weight: bold; background-color: #c1ffc1;">
                    TGL REGISTRASI</th>
            </tr>
        </thead>

        <tbody>
            @forelse($patients as $index => $patient)
                @php
                    $activePregnancy = $patient->pregnancies->where('status', 'aktif')->first();
                    $latestPregnancy = $patient->pregnancies->first();

                    $statusText = 'BELUM ADA';
                    $statusColor = '#721C24';
                    $statusBg = '#F8D7DA';

                    if ($activePregnancy) {
                        $statusText = 'AKTIF';
                        $statusColor = '#155724';
                        $statusBg = '#D4EDDA';
                    } elseif ($latestPregnancy) {
                        $statusText = strtoupper($latestPregnancy->status);
                        $statusColor = '#856404';
                        $statusBg = '#FFF3CD';
                    }
                @endphp
                <tr>
                    <!-- NO -->
                    <td style="border: 1px solid black; padding: 5px; text-align: center; vertical-align: middle;">
                        {{ $index + 1 }}</td>

                    <!-- NO RM -->
                    <td style="border: 1px solid black; padding: 5px; text-align: center; vertical-align: middle;">
                        {{ $patient->no_rm }}</td>

                    <!-- NIK -->
                    <td style="border: 1px solid black; padding: 5px; text-align: center; vertical-align: middle;">
                        {{ $patient->nik ? "'".$patient->nik : '-' }}</td>

                    <!-- NO KK -->
                    <td style="border: 1px solid black; padding: 5px; text-align: center; vertical-align: middle;">
                        {{ $patient->no_kk ? "'".$patient->no_kk : '-' }}</td>

                    <!-- NO BPJS -->
                    <td style="border: 1px solid black; padding: 5px; text-align: center; vertical-align: middle;">
                        {{ $patient->no_bpjs ? "'".$patient->no_bpjs : '-' }}</td>

                    <!-- NAMA -->
                    <td style="border: 1px solid black; padding: 5px; vertical-align: middle;">{{ $patient->name }}</td>

                    <!-- TEMPAT LAHIR -->
                    <td style="border: 1px solid black; padding: 5px; vertical-align: middle;">
                        {{ $patient->pob ?? '-' }}</td>

                    <!-- TGL LAHIR -->
                    <td style="border: 1px solid black; padding: 5px; text-align: center; vertical-align: middle;">
                        {{ $patient->dob ? $patient->dob->format('d/m/Y') : '-' }}</td>

                    <!-- UMUR -->
                    <td style="border: 1px solid black; padding: 5px; text-align: center; vertical-align: middle;">
                        {{ $patient->dob ? $patient->dob->age : '-' }}</td>

                    <!-- PENDIDIKAN -->
                    <td style="border: 1px solid black; padding: 5px; vertical-align: middle;">
                        {{ $patient->education ?? '-' }}</td>

                    <!-- PEKERJAAN -->
                    <td style="border: 1px solid black; padding: 5px; vertical-align: middle;">
                        {{ $patient->job ?? '-' }}</td>

                    <!-- GOL DARAH -->
                    <td style="border: 1px solid black; padding: 5px; text-align: center; vertical-align: middle;">
                        {{ $patient->blood_type ?? '-' }}</td>

                    <!-- ALAMAT -->
                    <td style="border: 1px solid black; padding: 5px; vertical-align: middle;">
                        {{ $patient->address ?? '-' }}</td>

                    <!-- NO HP -->
                    <td style="border: 1px solid black; padding: 5px; text-align: center; vertical-align: middle;">
                        {{ $patient->phone ? "'".$patient->phone : '-' }}</td>

                    <!-- NAMA SUAMI -->
                    <td style="border: 1px solid black; padding: 5px; vertical-align: middle;">
                        {{ $patient->husband_name ?? '-' }}</td>

                    <!-- NIK SUAMI -->
                    <td style="border: 1px solid black; padding: 5px; text-align: center; vertical-align: middle;">
                        {{ $patient->husband_nik ? "'".$patient->husband_nik : '-' }}</td>

                    <!-- PENDIDIKAN SUAMI -->
                    <td style="border: 1px solid black; padding: 5px; vertical-align: middle;">
                        {{ $patient->husband_education ?? '-' }}</td>

                    <!-- PEKERJAAN SUAMI -->
                    <td style="border: 1px solid black; padding: 5px; vertical-align: middle;">
                        {{ $patient->husband_job ?? '-' }}</td>

                    <!-- GOL DARAH SUAMI -->
                    <td style="border: 1px solid black; padding: 5px; text-align: center; vertical-align: middle;">
                        {{ $patient->husband_blood_type ?? '-' }}</td>

                    <!-- STATUS KEHAMILAN -->
                    <td style="border: 1px solid black; padding: 5px; text-align: center; vertical-align: middle;">
                        <span style="background-color: {{ $statusBg }}; color: {{ $statusColor }}; padding: 3px 8px; border-radius: 3px; font-weight: bold;">
                            {{ $statusText }}
                        </span>
                    </td>

                    <!-- TGL REGISTRASI -->
                    <td style="border: 1px solid black; padding: 5px; text-align: center; vertical-align: middle;">
                        {{ $patient->created_at->format('d/m/Y') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="21"
                        style="border: 1px solid black; padding: 20px; text-align: center; vertical-align: middle; font-style: italic;">
                        Tidak ada data pasien yang ditemukan.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>
