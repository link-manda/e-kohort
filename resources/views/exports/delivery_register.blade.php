<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Reg. Persalinan {{ \Carbon\Carbon::createFromDate($year, $month, 1)->locale('id')->isoFormat('MMMM YYYY') }}
    </title>
</head>

<body>
    <table style="border-collapse: collapse; width: 100%;">
        <!-- Title Row -->
        <thead>
            <tr>
                <th colspan="27"
                    style="border: 1px solid black; padding: 10px; text-align: center; font-size: 16px; font-weight: bold;">
                    REGISTER PERSALINAN
                    <br>
                    BULAN:
                    {{ strtoupper(\Carbon\Carbon::createFromDate($year, $month, 1)->locale('id')->isoFormat('MMMM YYYY')) }}
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
                    NO KK</th>

                <!-- IBU (Parent Header - Colspan 5) -->
                <th colspan="5"
                    style="border: 1px solid black; padding: 8px; text-align: center; vertical-align: middle; font-weight: bold; background-color: #ffc0cb;">
                    IBU</th>

                <!-- SUAMI (Parent Header - Colspan 5) -->
                <th colspan="5"
                    style="border: 1px solid black; padding: 8px; text-align: center; vertical-align: middle; font-weight: bold; background-color: #add8e6;">
                    SUAMI</th>

                <th rowspan="2"
                    style="border: 1px solid black; padding: 8px; text-align: center; vertical-align: middle; font-weight: bold; background-color: #f0f0f0;">
                    ALAMAT</th>
                <th rowspan="2"
                    style="border: 1px solid black; padding: 8px; text-align: center; vertical-align: middle; font-weight: bold; background-color: #f0f0f0;">
                    TGL/JAM<br>BERSALIN</th>
                <th rowspan="2"
                    style="border: 1px solid black; padding: 8px; text-align: center; vertical-align: middle; font-weight: bold; background-color: #f0f0f0;">
                    JENIS<br>PERSALINAN</th>
                <th rowspan="2"
                    style="border: 1px solid black; padding: 8px; text-align: center; vertical-align: middle; font-weight: bold; background-color: #f0f0f0;">
                    PENYULIT</th>
                <th rowspan="2"
                    style="border: 1px solid black; padding: 8px; text-align: center; vertical-align: middle; font-weight: bold; background-color: #f0f0f0;">
                    EPISIOTOMI</th>

                <!-- MANAJEMEN AKTIF KALA 3 (Parent Header - Colspan 3) -->
                <th colspan="3"
                    style="border: 1px solid black; padding: 8px; text-align: center; vertical-align: middle; font-weight: bold; background-color: #ffe4b5;">
                    MANAJEMEN<br>AKTIF KALA 3</th>

                <th rowspan="2"
                    style="border: 1px solid black; padding: 8px; text-align: center; vertical-align: middle; font-weight: bold; background-color: #f0f0f0;">
                    PEMANTAUAN<br>2 JAM PP</th>
                <th rowspan="2"
                    style="border: 1px solid black; padding: 8px; text-align: center; vertical-align: middle; font-weight: bold; background-color: #f0f0f0;">
                    KETERANGAN</th>
            </tr>

            <!-- Child Headers (Row 2) -->
            <tr>
                <!-- IBU Child Headers -->
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
                    HP</th>

                <!-- SUAMI Child Headers -->
                <th
                    style="border: 1px solid black; padding: 6px; text-align: center; vertical-align: middle; font-weight: bold; background-color: #87ceeb;">
                    NAMA</th>
                <th
                    style="border: 1px solid black; padding: 6px; text-align: center; vertical-align: middle; font-weight: bold; background-color: #87ceeb;">
                    NIK</th>
                <th
                    style="border: 1px solid black; padding: 6px; text-align: center; vertical-align: middle; font-weight: bold; background-color: #87ceeb;">
                    TTL</th>
                <th
                    style="border: 1px solid black; padding: 6px; text-align: center; vertical-align: middle; font-weight: bold; background-color: #87ceeb;">
                    UMUR</th>
                <th
                    style="border: 1px solid black; padding: 6px; text-align: center; vertical-align: middle; font-weight: bold; background-color: #87ceeb;">
                    HP</th>

                <!-- MANAJEMEN AKTIF KALA 3 Child Headers -->
                <th
                    style="border: 1px solid black; padding: 6px; text-align: center; vertical-align: middle; font-weight: bold; background-color: #ffd699;">
                    Suntik<br>Oksitosin</th>
                <th
                    style="border: 1px solid black; padding: 6px; text-align: center; vertical-align: middle; font-weight: bold; background-color: #ffd699;">
                    PTT/<br>Penjahitan</th>
                <th
                    style="border: 1px solid black; padding: 6px; text-align: center; vertical-align: middle; font-weight: bold; background-color: #ffd699;">
                    Masase<br>Uterus</th>
            </tr>
        </thead>

        <tbody>
            @forelse($deliveries as $index => $delivery)
                @php
                    $patient = $delivery->pregnancy->patient;

                    // Calculate age at delivery
                    $ageAtDelivery = $patient->date_of_birth
                        ? \Carbon\Carbon::parse($patient->date_of_birth)->diffInYears(
                            \Carbon\Carbon::parse($delivery->delivery_date_time),
                        )
                        : '-';

                    // Format TTL (Tempat, Tanggal Lahir)
                    $motherTTL =
                        ($patient->place_of_birth ?? '-') .
                        ', ' .
                        ($patient->date_of_birth
                            ? \Carbon\Carbon::parse($patient->date_of_birth)->locale('id')->isoFormat('D MMM YYYY')
                            : '-');

                    // Husband data
                    $husbandName = $patient->husband_name ?? '-';
                    $husbandNIK = $patient->husband_nik ?? '-';
                    $husbandDOB = $patient->husband_dob;
                    $husbandTTL =
                        ($patient->husband_pob ?? '-') .
                        ', ' .
                        ($husbandDOB ? \Carbon\Carbon::parse($husbandDOB)->locale('id')->isoFormat('D MMM YYYY') : '-');
                    $husbandAge = $husbandDOB
                        ? \Carbon\Carbon::parse($husbandDOB)->diffInYears(
                            \Carbon\Carbon::parse($delivery->delivery_date_time),
                        )
                        : '-';
                    $husbandPhone = $patient->husband_phone ?? '-';

                    // Delivery details
                    $deliveryDateTime = \Carbon\Carbon::parse($delivery->delivery_date_time)
                        ->locale('id')
                        ->isoFormat('D/M/YY HH:mm');

                    // Episiotomi check
                    $episiotomi =
                        stripos($delivery->perineum_rupture, 'Episiotomi') !== false
                            ? 'Ya'
                            : (stripos($delivery->perineum_rupture, 'Derajat') !== false
                                ? $delivery->perineum_rupture
                                : 'Tidak');

                    // AMTSL checkboxes
                    $oksitocin = $delivery->oxytocin_injection ? '✓' : '-';
                    $ptt = $delivery->controlled_cord_traction ? '✓' : '-';
                    $masase = $delivery->uterine_massage ? '✓' : '-';

                    // Complications
                    $complications = $delivery->complications ?: ($delivery->pregnancy->risk_notes ?: 'Tidak ada');

                    // Additional notes
                    $keterangan = [];
                    if ($delivery->baby_name) {
                        $keterangan[] = 'Bayi: ' . $delivery->baby_name;
                    }
                    if ($delivery->birth_weight) {
                        $keterangan[] = 'BB: ' . $delivery->birth_weight . 'g';
                    }
                    if ($delivery->condition) {
                        $keterangan[] = 'Kondisi: ' . $delivery->condition;
                    }
                    $keteranganText = implode(', ', $keterangan) ?: '-';
                @endphp
                <tr>
                    <!-- NO -->
                    <td style="border: 1px solid black; padding: 5px; text-align: center; vertical-align: middle;">
                        {{ $index + 1 }}</td>

                    <!-- TANGGAL -->
                    <td style="border: 1px solid black; padding: 5px; text-align: center; vertical-align: middle;">
                        {{ \Carbon\Carbon::parse($delivery->delivery_date_time)->format('d/m/Y') }}</td>

                    <!-- NO KK -->
                    <td style="border: 1px solid black; padding: 5px; text-align: center; vertical-align: middle;">
                        {{ $patient->family_card_number ?? '-' }}</td>

                    <!-- IBU: NAMA -->
                    <td style="border: 1px solid black; padding: 5px; vertical-align: middle;">{{ $patient->name }}</td>

                    <!-- IBU: NIK -->
                    <td style="border: 1px solid black; padding: 5px; text-align: center; vertical-align: middle;">
                        {{ $patient->nik ?? '-' }}</td>

                    <!-- IBU: TTL -->
                    <td style="border: 1px solid black; padding: 5px; vertical-align: middle;">{{ $motherTTL }}</td>

                    <!-- IBU: UMUR -->
                    <td style="border: 1px solid black; padding: 5px; text-align: center; vertical-align: middle;">
                        {{ $ageAtDelivery }} th</td>

                    <!-- IBU: HP -->
                    <td style="border: 1px solid black; padding: 5px; text-align: center; vertical-align: middle;">
                        {{ $patient->phone ?? '-' }}</td>

                    <!-- SUAMI: NAMA -->
                    <td style="border: 1px solid black; padding: 5px; vertical-align: middle;">{{ $husbandName }}</td>

                    <!-- SUAMI: NIK -->
                    <td style="border: 1px solid black; padding: 5px; text-align: center; vertical-align: middle;">
                        {{ $husbandNIK }}</td>

                    <!-- SUAMI: TTL -->
                    <td style="border: 1px solid black; padding: 5px; vertical-align: middle;">{{ $husbandTTL }}</td>

                    <!-- SUAMI: UMUR -->
                    <td style="border: 1px solid black; padding: 5px; text-align: center; vertical-align: middle;">
                        {{ $husbandAge }} th</td>

                    <!-- SUAMI: HP -->
                    <td style="border: 1px solid black; padding: 5px; text-align: center; vertical-align: middle;">
                        {{ $husbandPhone }}</td>

                    <!-- ALAMAT -->
                    <td style="border: 1px solid black; padding: 5px; vertical-align: middle;">
                        {{ $patient->address ?? '-' }}</td>

                    <!-- TGL/JAM BERSALIN -->
                    <td style="border: 1px solid black; padding: 5px; text-align: center; vertical-align: middle;">
                        {{ $deliveryDateTime }}</td>

                    <!-- JENIS PERSALINAN -->
                    <td style="border: 1px solid black; padding: 5px; text-align: center; vertical-align: middle;">
                        {{ $delivery->delivery_method ?? '-' }}</td>

                    <!-- PENYULIT -->
                    <td style="border: 1px solid black; padding: 5px; vertical-align: middle;">{{ $complications }}
                    </td>

                    <!-- EPISIOTOMI -->
                    <td style="border: 1px solid black; padding: 5px; text-align: center; vertical-align: middle;">
                        {{ $episiotomi }}</td>

                    <!-- MANAJEMEN KALA 3: Oksitosin -->
                    <td
                        style="border: 1px solid black; padding: 5px; text-align: center; vertical-align: middle; font-weight: bold;">
                        {{ $oksitocin }}</td>

                    <!-- MANAJEMEN KALA 3: PTT -->
                    <td
                        style="border: 1px solid black; padding: 5px; text-align: center; vertical-align: middle; font-weight: bold;">
                        {{ $ptt }}</td>

                    <!-- MANAJEMEN KALA 3: Masase -->
                    <td
                        style="border: 1px solid black; padding: 5px; text-align: center; vertical-align: middle; font-weight: bold;">
                        {{ $masase }}</td>

                    <!-- PEMANTAUAN 2 JAM PP -->
                    <td style="border: 1px solid black; padding: 5px; vertical-align: middle;">
                        {{ $delivery->postpartum_monitoring_2h ?? '-' }}</td>

                    <!-- KETERANGAN -->
                    <td style="border: 1px solid black; padding: 5px; vertical-align: middle;">{{ $keteranganText }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="27"
                        style="border: 1px solid black; padding: 20px; text-align: center; vertical-align: middle; font-style: italic;">
                        Tidak ada data persalinan pada bulan
                        {{ \Carbon\Carbon::createFromDate($year, $month, 1)->locale('id')->isoFormat('MMMM YYYY') }}
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>
