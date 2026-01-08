<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Bulanan - {{ $data['period']['month_name'] }} {{ $data['period']['year'] }}</title>
    <style>
        @page {
            size: A4;
            margin: 2cm 1.5cm;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 10pt;
            color: #333;
            line-height: 1.4;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 3px solid #4472C4;
        }

        .header h1 {
            margin: 0 0 5px 0;
            font-size: 18pt;
            color: #4472C4;
            font-weight: bold;
        }

        .header .subtitle {
            font-size: 11pt;
            color: #666;
            margin: 5px 0;
        }

        .header .clinic-info {
            font-size: 9pt;
            color: #888;
            margin-top: 10px;
        }

        .section {
            margin-bottom: 25px;
            page-break-inside: avoid;
        }

        .section-title {
            font-size: 12pt;
            font-weight: bold;
            color: #4472C4;
            margin-bottom: 10px;
            padding: 8px 12px;
            background-color: #E7F3FF;
            border-left: 4px solid #4472C4;
        }

        .stats-grid {
            display: table;
            width: 100%;
            margin-bottom: 15px;
        }

        .stats-row {
            display: table-row;
        }

        .stats-cell {
            display: table-cell;
            width: 25%;
            padding: 12px;
            border: 1px solid #ddd;
            text-align: center;
            background-color: #f9f9f9;
        }

        .stats-cell .label {
            font-size: 8pt;
            color: #666;
            margin-bottom: 5px;
            display: block;
        }

        .stats-cell .value {
            font-size: 20pt;
            font-weight: bold;
            color: #4472C4;
            display: block;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        th {
            background-color: #4472C4;
            color: white;
            padding: 8px;
            text-align: left;
            font-weight: bold;
            font-size: 9pt;
        }

        td {
            padding: 6px 8px;
            border: 1px solid #ddd;
            font-size: 9pt;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .highlight-box {
            padding: 10px;
            margin: 10px 0;
            border-left: 4px solid #FFC107;
            background-color: #FFF9E6;
        }

        .risk-high {
            color: #D32F2F;
            font-weight: bold;
        }

        .risk-moderate {
            color: #F57C00;
            font-weight: bold;
        }

        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 8pt;
            color: #999;
            padding-top: 10px;
            border-top: 1px solid #ddd;
        }

        .signature-section {
            margin-top: 40px;
            text-align: right;
        }

        .signature-box {
            display: inline-block;
            text-align: center;
            margin-top: 20px;
        }

        .signature-line {
            width: 200px;
            border-bottom: 1px solid #333;
            margin: 60px auto 5px;
        }

        .two-column {
            display: table;
            width: 100%;
        }

        .column {
            display: table-cell;
            width: 50%;
            padding-right: 15px;
            vertical-align: top;
        }

        .column:last-child {
            padding-right: 0;
            padding-left: 15px;
        }

        .data-row {
            padding: 5px 10px;
            margin-bottom: 5px;
            border-bottom: 1px solid #eee;
            display: table;
            width: 100%;
        }

        .data-row .label {
            display: table-cell;
            width: 70%;
            font-size: 9pt;
            color: #555;
        }

        .data-row .value {
            display: table-cell;
            width: 30%;
            text-align: right;
            font-weight: bold;
            font-size: 10pt;
            color: #333;
        }

        .visits-grid {
            display: table;
            width: 100%;
            margin: 15px 0;
        }

        .visits-cell {
            display: table-cell;
            width: 12.5%;
            padding: 15px 5px;
            text-align: center;
            border: 1px solid #ddd;
            background: linear-gradient(to bottom, #E7F3FF, #ffffff);
        }

        .visits-cell .code {
            font-weight: bold;
            font-size: 11pt;
            color: #4472C4;
            display: block;
            margin-bottom: 5px;
        }

        .visits-cell .count {
            font-size: 18pt;
            font-weight: bold;
            color: #333;
            display: block;
        }
    </style>
</head>

<body>
    <!-- Header -->
    <div class="header">
        <h1>📊 LAPORAN RINGKASAN BULANAN</h1>
        <div class="subtitle">Program Antenatal Care (ANC) Terintegrasi</div>
        <div class="subtitle">
            <strong>Periode: {{ $data['period']['month_name'] }} {{ $data['period']['year'] }}</strong>
        </div>
        <div class="clinic-info">
            Klinik Bidan | Sistem E-Kohort | Tanggal Cetak: {{ now()->locale('id')->isoFormat('D MMMM Y') }}
        </div>
    </div>

    <!-- Summary Statistics -->
    <div class="section">
        <div class="section-title">I. RINGKASAN STATISTIK</div>
        <div class="stats-grid">
            <div class="stats-row">
                <div class="stats-cell">
                    <span class="label">Pasien Baru</span>
                    <span class="value">{{ $data['new_patients'] ?? 0 }}</span>
                </div>
                <div class="stats-cell">
                    <span class="label">Kehamilan Baru</span>
                    <span class="value">{{ $data['new_pregnancies'] ?? 0 }}</span>
                </div>
                <div class="stats-cell">
                    <span class="label">Total Kunjungan</span>
                    <span class="value">{{ $data['total_visits'] ?? 0 }}</span>
                </div>
                <div class="stats-cell">
                    <span class="label">Resiko Tinggi</span>
                    <span class="value" style="color: #D32F2F;">{{ $data['high_risk_count'] ?? 0 }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Kunjungan ANC per Kode -->
    <div class="section">
        <div class="section-title">II. DISTRIBUSI KUNJUNGAN ANC</div>
        <div class="visits-grid">
            @foreach (['K1', 'K2', 'K3', 'K4', 'K5', 'K6', 'K7', 'K8'] as $code)
                <div class="visits-cell">
                    <span class="code">{{ $code }}</span>
                    <span class="count">{{ $data['visits_by_code'][$code] ?? 0 }}</span>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Risk Factors -->
    <div class="section">
        <div class="section-title">III. FAKTOR RESIKO & KOMPLIKASI</div>
        <div class="two-column">
            <div class="column">
                <table>
                    <thead>
                        <tr>
                            <th>Kategori Resiko</th>
                            <th style="text-align: center; width: 80px;">Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>MAP Ekstrem (>100) <span class="risk-high">⚠️</span></td>
                            <td style="text-align: center;"><strong
                                    class="risk-high">{{ $data['extreme_risk_count'] ?? 0 }}</strong></td>
                        </tr>
                        <tr>
                            <td>MAP Tinggi (>90) <span class="risk-moderate">⚠️</span></td>
                            <td style="text-align: center;"><strong
                                    class="risk-moderate">{{ $data['high_risk_count'] ?? 0 }}</strong></td>
                        </tr>
                        <tr>
                            <td>KEK (LILA < 23.5 cm)</td>
                            <td style="text-align: center;"><strong>{{ $data['kek_count'] ?? 0 }}</strong></td>
                        </tr>
                        <tr>
                            <td>Anemia (Hb < 11 g/dL)</td>
                            <td style="text-align: center;"><strong>{{ $data['anemia_count'] ?? 0 }}</strong></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="column">
                <div class="highlight-box">
                    <strong>⚠️ PERHATIAN KHUSUS</strong><br>
                    <div style="margin-top: 10px;">
                        <div class="data-row">
                            <span class="label">Total Rujukan:</span>
                            <span class="value">{{ $data['referrals'] ?? 0 }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Triple Eliminasi -->
    <div class="section">
        <div class="section-title">IV. TRIPLE ELIMINASI SCREENING</div>
        <table>
            <thead>
                <tr>
                    <th>Jenis Screening</th>
                    <th style="text-align: center; width: 120px;">Jumlah Tes</th>
                    <th style="text-align: center; width: 120px;">Hasil Reaktif</th>
                    <th style="text-align: center; width: 100px;">Persentase</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>HIV Screening</td>
                    <td style="text-align: center;">{{ $data['triple_eliminasi']['hiv_tested'] ?? 0 }}</td>
                    <td style="text-align: center;"><strong
                            class="risk-high">{{ $data['triple_eliminasi']['hiv_reactive'] ?? 0 }}</strong></td>
                    <td style="text-align: center;">
                        @php
                            $tested = $data['triple_eliminasi']['hiv_tested'] ?? 0;
                            $reactive = $data['triple_eliminasi']['hiv_reactive'] ?? 0;
                            $percentage = $tested > 0 ? round(($reactive / $tested) * 100, 1) : 0;
                        @endphp
                        {{ $percentage }}%
                    </td>
                </tr>
                <tr>
                    <td>Syphilis Screening</td>
                    <td style="text-align: center;">{{ $data['triple_eliminasi']['syphilis_tested'] ?? 0 }}</td>
                    <td style="text-align: center;"><strong
                            class="risk-high">{{ $data['triple_eliminasi']['syphilis_reactive'] ?? 0 }}</strong></td>
                    <td style="text-align: center;">
                        @php
                            $tested = $data['triple_eliminasi']['syphilis_tested'] ?? 0;
                            $reactive = $data['triple_eliminasi']['syphilis_reactive'] ?? 0;
                            $percentage = $tested > 0 ? round(($reactive / $tested) * 100, 1) : 0;
                        @endphp
                        {{ $percentage }}%
                    </td>
                </tr>
                <tr>
                    <td>HBsAg Screening</td>
                    <td style="text-align: center;">{{ $data['triple_eliminasi']['hbsag_tested'] ?? 0 }}</td>
                    <td style="text-align: center;"><strong
                            class="risk-high">{{ $data['triple_eliminasi']['hbsag_reactive'] ?? 0 }}</strong></td>
                    <td style="text-align: center;">
                        @php
                            $tested = $data['triple_eliminasi']['hbsag_tested'] ?? 0;
                            $reactive = $data['triple_eliminasi']['hbsag_reactive'] ?? 0;
                            $percentage = $tested > 0 ? round(($reactive / $tested) * 100, 1) : 0;
                        @endphp
                        {{ $percentage }}%
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Interventions -->
    <div class="section">
        <div class="section-title">V. INTERVENSI & LAYANAN</div>
        <div class="two-column">
            <div class="column">
                <h4 style="color: #4472C4; margin-bottom: 10px;">Imunisasi TT</h4>
                <table>
                    <thead>
                        <tr>
                            <th>Jenis</th>
                            <th style="text-align: center; width: 80px;">Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach (['tt1' => 'TT1', 'tt2' => 'TT2', 'tt3' => 'TT3', 'tt4' => 'TT4', 'tt5' => 'TT5'] as $key => $label)
                            <tr>
                                <td>{{ $label }}</td>
                                <td style="text-align: center;">
                                    <strong>{{ $data['tt_immunization'][$key] ?? 0 }}</strong></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="column">
                <h4 style="color: #4472C4; margin-bottom: 10px;">Layanan Lainnya</h4>
                <div class="data-row">
                    <span class="label">ANC 12T Lengkap:</span>
                    <span class="value">{{ $data['anc_12t_complete'] ?? 0 }}</span>
                </div>
                <div class="data-row">
                    <span class="label">USG Screening:</span>
                    <span class="value">{{ $data['usg_count'] ?? 0 }}</span>
                </div>
                <div class="data-row">
                    <span class="label">Konseling:</span>
                    <span class="value">{{ $data['counseling_count'] ?? 0 }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Signature -->
    <div class="signature-section">
        <div style="margin-top: 30px;">
            <p style="margin: 0;">Denpasar, {{ now()->locale('id')->isoFormat('D MMMM Y') }}</p>
            <p style="margin: 5px 0 0 0; font-weight: bold;">Bidan Koordinator</p>
            <div class="signature-line"></div>
            <p style="margin: 5px 0 0 0; font-weight: bold;">{{ auth()->user()->name ?? 'Nama Bidan' }}</p>
            <p style="margin: 0; font-size: 9pt; color: #666;">NIP: ___________________</p>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        Laporan ini digenerate secara otomatis oleh Sistem E-Kohort Klinik | Halaman {PAGE_NUM} dari {PAGE_COUNT}
    </div>
</body>

</html>
