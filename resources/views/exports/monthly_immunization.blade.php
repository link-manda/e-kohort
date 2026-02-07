<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Reg. Imunisasi {{ $dateFormatted }}</title>
</head>

<body>
    <table style="border-collapse: collapse; width: 100%;">
        <!-- Title Row -->
        <thead>
            <tr>
                <th colspan="17"
                    style="border: 1px solid black; padding: 10px; text-align: center; font-size: 16px; font-weight: bold;">
                    REGISTER PELAYANAN IMUNISASI
                    <br>
                    BULAN: {{ strtoupper($dateFormatted) }}
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

                <!-- IDENTITAS BAYI (Parent Header - Colspan 4) -->
                <th colspan="4"
                    style="border: 1px solid black; padding: 8px; text-align: center; vertical-align: middle; font-weight: bold; background-color: #ffc0cb;">
                    IDENTITAS BAYI</th>

                <!-- ORANG TUA (Parent Header - Colspan 3) -->
                <th colspan="3"
                    style="border: 1px solid black; padding: 8px; text-align: center; vertical-align: middle; font-weight: bold; background-color: #add8e6;">
                    ORANG TUA</th>

                <!-- ANTROPOMETRI (Parent Header - Colspan 4) -->
                <th colspan="4"
                    style="border: 1px solid black; padding: 8px; text-align: center; vertical-align: middle; font-weight: bold; background-color: #ffe4b5;">
                    ANTROPOMETRI</th>

                <!-- PELAYANAN (Parent Header - Colspan 3) -->
                <th colspan="3"
                    style="border: 1px solid black; padding: 8px; text-align: center; vertical-align: middle; font-weight: bold; background-color: #98fb98;">
                    PELAYANAN</th>

                <th rowspan="2"
                    style="border: 1px solid black; padding: 8px; text-align: center; vertical-align: middle; font-weight: bold; background-color: #f0f0f0;">
                    KETERANGAN</th>
            </tr>

            <!-- Child Headers (Row 2) -->
            <tr>
                <!-- IDENTITAS BAYI Child Headers -->
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

                <!-- ORANG TUA Child Headers -->
                <th
                    style="border: 1px solid black; padding: 6px; text-align: center; vertical-align: middle; font-weight: bold; background-color: #87ceeb;">
                    NAMA</th>
                <th
                    style="border: 1px solid black; padding: 6px; text-align: center; vertical-align: middle; font-weight: bold; background-color: #87ceeb;">
                    NIK</th>
                <th
                    style="border: 1px solid black; padding: 6px; text-align: center; vertical-align: middle; font-weight: bold; background-color: #87ceeb;">
                    ALAMAT/HP</th>

                <!-- ANTROPOMETRI Child Headers -->
                <th
                    style="border: 1px solid black; padding: 6px; text-align: center; vertical-align: middle; font-weight: bold; background-color: #ffd699;">
                    BB (kg)</th>
                <th
                    style="border: 1px solid black; padding: 6px; text-align: center; vertical-align: middle; font-weight: bold; background-color: #ffd699;">
                    TB (cm)</th>
                <th
                    style="border: 1px solid black; padding: 6px; text-align: center; vertical-align: middle; font-weight: bold; background-color: #ffd699;">
                    LK (cm)</th>
                <th
                    style="border: 1px solid black; padding: 6px; text-align: center; vertical-align: middle; font-weight: bold; background-color: #ffd699;">
                    STS GIZI</th>

                <!-- PELAYANAN Child Headers -->
                <th
                    style="border: 1px solid black; padding: 6px; text-align: center; vertical-align: middle; font-weight: bold; background-color: #c1ffc1;">
                    VAKSIN</th>
                <th
                    style="border: 1px solid black; padding: 6px; text-align: center; vertical-align: middle; font-weight: bold; background-color: #c1ffc1;">
                    VIT A</th>
                <th
                    style="border: 1px solid black; padding: 6px; text-align: center; vertical-align: middle; font-weight: bold; background-color: #c1ffc1;">
                    OBAT</th>
            </tr>
        </thead>

        <tbody>
            @forelse($visits as $index => $visit)
                @php
                    $child = $visit->child;
                    $patient = $child->patient; // Mother's data if linked

// Format TTL
$ttl =
    ($child->pob ?? '-') .
    ', ' .
    ($child->dob ? \Carbon\Carbon::parse($child->dob)->locale('id')->isoFormat('D MMM YYYY') : '-');

// Age - round to integer
$age = $child->dob
    ? round(\Carbon\Carbon::parse($child->dob)->diffInMonths(\Carbon\Carbon::parse($visit->visit_date))) .
        ' bln'
    : '-';

// Parents
$parentName = $patient ? $patient->name : $child->mother_name ?? '-';
$parentNik = $patient ? $patient->nik : $child->mother_nik ?? '-';
$addressHp =
    ($patient ? $patient->address ?? '-' : $child->address ?? '-') .
    ' / ' .
    ($patient ? $patient->phone ?? '-' : $child->parent_phone ?? '-');

// Vaccines
$vaccines = $visit->immunizationActions
    ->map(function ($a) {
        if (isset($a->vaccine) && $a->vaccine && isset($a->vaccine->name)) {
            return $a->vaccine->name;
        }
        return $a->vaccine_type ? str_replace('_', ' ', $a->vaccine_type) : null;
    })
    ->filter()
    ->unique()
    ->values()
    ->implode(', ');

// Vitamin A
$vitA = $visit->vitamin_a_given ? 'Ya' : '-';

// Medicine
$medicine = $visit->medicine_given
    ? $visit->medicine_given . ($visit->medicine_dosage ? ' (' . $visit->medicine_dosage . ')' : '')
    : '-';

                @endphp
                <tr>
                    <!-- NO -->
                    <td style="border: 1px solid black; padding: 5px; text-align: center; vertical-align: middle;">
                        {{ $index + 1 }}</td>

                    <!-- TANGGAL -->
                    <td style="border: 1px solid black; padding: 5px; text-align: center; vertical-align: middle;">
                        {{ $visit->visit_date->format('d/m/Y') }}</td>

                    <!-- NAMA BAYI -->
                    <td style="border: 1px solid black; padding: 5px; vertical-align: middle;">{{ $child->name }}</td>

                    <!-- NIK BAYI -->
                    <td style="border: 1px solid black; padding: 5px; text-align: center; vertical-align: middle;">
                        {{ $child->nik ?? '-' }}</td>

                    <!-- TTL BAYI -->
                    <td style="border: 1px solid black; padding: 5px; vertical-align: middle;">{{ $ttl }}</td>

                    <!-- UMUR -->
                    <td style="border: 1px solid black; padding: 5px; text-align: center; vertical-align: middle;">
                        {{ $age }}</td>

                    <!-- NAMA ORTU -->
                    <td style="border: 1px solid black; padding: 5px; vertical-align: middle;">{{ $parentName }}</td>

                    <!-- NIK ORTU -->
                    <td style="border: 1px solid black; padding: 5px; text-align: center; vertical-align: middle;">
                        {{ $parentNik }}</td>

                    <!-- ALAMAT/HP -->
                    <td style="border: 1px solid black; padding: 5px; vertical-align: middle;">
                        {{ $addressHp }}</td>

                    <!-- BB -->
                    <td style="border: 1px solid black; padding: 5px; text-align: center; vertical-align: middle;">
                        {{ $visit->weight ?? '-' }}</td>

                    <!-- TB -->
                    <td style="border: 1px solid black; padding: 5px; text-align: center; vertical-align: middle;">
                        {{ $visit->height ?? '-' }}</td>

                    <!-- LK -->
                    <td style="border: 1px solid black; padding: 5px; text-align: center; vertical-align: middle;">
                        {{ $visit->head_circumference ?? '-' }}</td>

                    <!-- STS GIZI -->
                    <td style="border: 1px solid black; padding: 5px; text-align: center; vertical-align: middle;">
                        {{ $visit->nutritional_status ?? '-' }}</td>

                    <!-- VAKSIN -->
                    <td style="border: 1px solid black; padding: 5px; vertical-align: middle;">
                        {{ $vaccines ?: '-' }}</td>

                    <!-- VIT A -->
                    <td style="border: 1px solid black; padding: 5px; text-align: center; vertical-align: middle;">
                        {{ $vitA }}</td>

                    <!-- OBAT -->
                    <td style="border: 1px solid black; padding: 5px; vertical-align: middle;">
                        {{ $medicine }}</td>

                    <!-- KETERANGAN -->
                    <td style="border: 1px solid black; padding: 5px; vertical-align: middle;">
                        {{ $visit->notes ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="17"
                        style="border: 1px solid black; padding: 20px; text-align: center; vertical-align: middle; font-style: italic;">
                        Tidak ada data imunisasi pada bulan {{ $dateFormatted }}.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>
