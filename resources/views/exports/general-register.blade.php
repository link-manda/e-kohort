<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Register Poli Umum</title>
</head>

<body>
    <table style="border-collapse: collapse; width: 100%;">
        <!-- Title Row -->
        <thead>
            <tr>
                <th colspan="13"
                    style="border: 1px solid black; padding: 10px; text-align: center; font-size: 16px; font-weight: bold;">
                    REGISTER RAWAT JALAN (POLI UMUM)
                    <br>
                    PERIODE: {{ strtoupper($period ?? '-') }}
                </th>
            </tr>

            <!-- Parent Headers (Row 1) -->
            <tr>
                <th rowspan="2"
                    style="border: 1px solid black; padding: 8px; text-align: center; vertical-align: middle; font-weight: bold; background-color: #f0f0f0;">
                    NO</th>
                <th rowspan="2"
                    style="border: 1px solid black; padding: 8px; text-align: center; vertical-align: middle; font-weight: bold; background-color: #f0f0f0;">
                    TANGGAL<br>KUNJUNGAN</th>

                <!-- IDENTITAS PASIEN (Parent Header - Colspan 5) -->
                <th colspan="5"
                    style="border: 1px solid black; padding: 8px; text-align: center; vertical-align: middle; font-weight: bold; background-color: #ffc0cb;">
                    IDENTITAS PASIEN</th>

                <!-- PELAYANAN (Parent Header - Colspan 3) -->
                <th colspan="3"
                    style="border: 1px solid black; padding: 8px; text-align: center; vertical-align: middle; font-weight: bold; background-color: #98fb98;">
                    PELAYANAN MEDIS</th>

                <!-- ADMINISTRASI (Parent Header - Colspan 2) -->
                <th colspan="2"
                    style="border: 1px solid black; padding: 8px; text-align: center; vertical-align: middle; font-weight: bold; background-color: #add8e6;">
                    ADMINISTRASI</th>

                <th rowspan="2"
                    style="border: 1px solid black; padding: 8px; text-align: center; vertical-align: middle; font-weight: bold; background-color: #f0f0f0;">
                    KETERANGAN</th>
            </tr>

            <!-- Child Headers (Row 2) -->
            <tr>
                <!-- IDENTITAS PASIEN Child Headers -->
                <th
                    style="border: 1px solid black; padding: 6px; text-align: center; vertical-align: middle; font-weight: bold; background-color: #ffb6c1;">
                    NO RM</th>
                <th
                    style="border: 1px solid black; padding: 6px; text-align: center; vertical-align: middle; font-weight: bold; background-color: #ffb6c1;">
                    NAMA</th>
                <th
                    style="border: 1px solid black; padding: 6px; text-align: center; vertical-align: middle; font-weight: bold; background-color: #ffb6c1;">
                    NO KK</th>
                <th
                    style="border: 1px solid black; padding: 6px; text-align: center; vertical-align: middle; font-weight: bold; background-color: #ffb6c1;">
                    UMUR</th>
                <th
                    style="border: 1px solid black; padding: 6px; text-align: center; vertical-align: middle; font-weight: bold; background-color: #ffb6c1;">
                    L/P</th>

                <!-- PELAYANAN MEDIS Child Headers -->
                <th
                    style="border: 1px solid black; padding: 6px; text-align: center; vertical-align: middle; font-weight: bold; background-color: #c1ffc1;">
                    KELUHAN</th>
                <th
                    style="border: 1px solid black; padding: 6px; text-align: center; vertical-align: middle; font-weight: bold; background-color: #c1ffc1;">
                    DIAGNOSA</th>
                <th
                    style="border: 1px solid black; padding: 6px; text-align: center; vertical-align: middle; font-weight: bold; background-color: #c1ffc1;">
                    TERAPI</th>

                <!-- ADMINISTRASI Child Headers -->
                <th
                    style="border: 1px solid black; padding: 6px; text-align: center; vertical-align: middle; font-weight: bold; background-color: #87ceeb;">
                    BIAYA</th>
                <th
                    style="border: 1px solid black; padding: 6px; text-align: center; vertical-align: middle; font-weight: bold; background-color: #87ceeb;">
                    PEMBAYARAN</th>
            </tr>
        </thead>

        <tbody>
            @forelse($visits as $index => $visit)
                @php
                    // Support both patient and child
                    $visitor = $visit->patient ?? $visit->child;
                    $visitorName = $visitor->name ?? '-';
                    $visitorAge = $visit->patient ? ($visit->patient->age ?? '-') . ' thn' : ($visit->child ? round($visit->child->age_in_months) . ' bln' : '-');
                    $visitorGender = $visitor->gender == 'Laki-laki' ? 'L' : 'P';
                    $visitorNoRm = $visitor->no_rm ?? '-';
                    $visitorNoKk = $visit->patient ? ($visit->patient->no_kk ?? '-') : ($visit->child ? ($visit->child->patient->no_kk ?? '-') : '-');

                    // Terapi - combine prescriptions
                    $therapy = '';
                    if ($visit->prescriptions && $visit->prescriptions->count() > 0) {
                        $therapy = $visit->prescriptions->map(function($p) {
                            return $p->medicine_name . ' (' . $p->quantity . ')';
                        })->implode(', ');
                    } else {
                        $therapy = $visit->therapy ?? '-';
                    }
                @endphp
                <tr>
                    <!-- NO -->
                    <td style="border: 1px solid black; padding: 5px; text-align: center; vertical-align: middle;">
                        {{ $index + 1 }}</td>

                    <!-- TANGGAL KUNJUNGAN -->
                    <td style="border: 1px solid black; padding: 5px; text-align: center; vertical-align: middle;">
                        {{ $visit->visit_date ? \Carbon\Carbon::parse($visit->visit_date)->format('d/m/Y H:i') : '-' }}
                    </td>

                    <!-- NO RM -->
                    <td style="border: 1px solid black; padding: 5px; text-align: center; vertical-align: middle;">
                        {{ $visitorNoRm }}</td>

                    <!-- NAMA -->
                    <td style="border: 1px solid black; padding: 5px; vertical-align: middle;">{{ $visitorName }}</td>

                    <!-- NO KK -->
                    <td style="border: 1px solid black; padding: 5px; text-align: center; vertical-align: middle;">
                        {{ $visitorNoKk }}</td>

                    <!-- UMUR -->
                    <td style="border: 1px solid black; padding: 5px; text-align: center; vertical-align: middle;">
                        {{ $visitorAge }}</td>

                    <!-- L/P -->
                    <td style="border: 1px solid black; padding: 5px; text-align: center; vertical-align: middle;">
                        {{ $visitorGender }}</td>

                    <!-- KELUHAN -->
                    <td style="border: 1px solid black; padding: 5px; vertical-align: middle;">
                        {{ $visit->complaint ?? '-' }}</td>

                    <!-- DIAGNOSA -->
                    <td style="border: 1px solid black; padding: 5px; vertical-align: middle;">
                        {{ $visit->diagnosis ?? ($visit->diagnosis_name ?? '-') }}</td>

                    <!-- TERAPI -->
                    <td style="border: 1px solid black; padding: 5px; vertical-align: middle;">{{ $therapy }}</td>

                    <!-- BIAYA -->
                    <td style="border: 1px solid black; padding: 5px; text-align: right; vertical-align: middle;">
                        {{ $visit->service_fee ? 'Rp ' . number_format($visit->service_fee, 0, ',', '.') : '-' }}</td>

                    <!-- PEMBAYARAN -->
                    <td style="border: 1px solid black; padding: 5px; text-align: center; vertical-align: middle;">
                        {{ $visit->payment_method ?? '-' }}</td>

                    <!-- KETERANGAN -->
                    <td style="border: 1px solid black; padding: 5px; vertical-align: middle;">
                        {{ $visit->notes ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="13"
                        style="border: 1px solid black; padding: 20px; text-align: center; vertical-align: middle; font-style: italic;">
                        Tidak ada data kunjungan pada periode {{ $period ?? 'ini' }}.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>
