<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekam Medis - {{ $patient->name }}</title>
    <style>
        /* Print-specific styles */
        @page {
            size: A4;
            margin: 2cm;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', 'Helvetica', sans-serif;
            font-size: 11pt;
            line-height: 1.4;
            color: #000;
            background: white;
        }

        /* Print only styles */
        @media print {
            body {
                print-color-adjust: exact;
                -webkit-print-color-adjust: exact;
            }

            .no-print {
                display: none !important;
            }

            .page-break {
                page-break-after: always;
            }

            .avoid-break {
                page-break-inside: avoid;
            }

            table {
                page-break-inside: auto;
            }

            tr {
                page-break-inside: avoid;
                page-break-after: auto;
            }
        }

        /* Screen only styles */
        @media screen {
            body {
                background: #f5f5f5;
                padding: 20px;
            }

            .print-container {
                max-width: 210mm;
                margin: 0 auto;
                background: white;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                padding: 2cm;
            }
        }

        /* Header */
        .header {
            border-bottom: 3px solid #2563eb;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }

        .clinic-info {
            text-align: center;
        }

        .clinic-name {
            font-size: 18pt;
            font-weight: bold;
            color: #2563eb;
            margin-bottom: 5px;
        }

        .clinic-address {
            font-size: 9pt;
            color: #666;
            margin-bottom: 3px;
        }

        .document-title {
            text-align: center;
            font-size: 14pt;
            font-weight: bold;
            margin: 20px 0;
            text-transform: uppercase;
            color: #1e40af;
        }

        /* Section styles */
        .section {
            margin-bottom: 20px;
        }

        .section-title {
            font-size: 12pt;
            font-weight: bold;
            background-color: #dbeafe;
            color: #1e40af;
            padding: 8px 12px;
            margin-bottom: 10px;
            border-left: 4px solid #2563eb;
        }

        /* Info table */
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        .info-table td {
            padding: 6px 8px;
            vertical-align: top;
        }

        .info-table td:first-child {
            width: 35%;
            font-weight: bold;
            color: #333;
        }

        .info-table td:nth-child(2) {
            width: 5%;
            color: #666;
        }

        .info-table td:last-child {
            color: #000;
        }

        /* Data table */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            font-size: 9pt;
        }

        .data-table th,
        .data-table td {
            border: 1px solid #d1d5db;
            padding: 6px 8px;
            text-align: left;
        }

        .data-table th {
            background-color: #f3f4f6;
            font-weight: bold;
            color: #1f2937;
        }

        .data-table tr:nth-child(even) {
            background-color: #f9fafb;
        }

        .data-table .text-center {
            text-align: center;
        }

        /* Badge styles */
        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 8pt;
            font-weight: bold;
        }

        .badge-active {
            background-color: #dcfce7;
            color: #166534;
        }

        .badge-complete {
            background-color: #fef3c7;
            color: #92400e;
        }

        .badge-high {
            background-color: #fee2e2;
            color: #991b1b;
        }

        .badge-normal {
            background-color: #dbeafe;
            color: #1e40af;
        }

        /* Footer */
        .footer {
            margin-top: 40px;
            text-align: right;
        }

        .signature-box {
            display: inline-block;
            text-align: center;
            margin-top: 10px;
        }

        .signature-line {
            border-top: 1px solid #000;
            margin-top: 60px;
            padding-top: 5px;
            min-width: 200px;
        }

        /* Print button */
        .print-button {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background-color: #2563eb;
            color: white;
            border: none;
            padding: 15px 30px;
            border-radius: 8px;
            font-size: 14pt;
            font-weight: bold;
            cursor: pointer;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
            transition: all 0.3s;
            z-index: 1000;
        }

        .print-button:hover {
            background-color: #1d4ed8;
            transform: translateY(-2px);
            box-shadow: 0 6px 8px rgba(0, 0, 0, 0.3);
        }

        /* Empty state */
        .empty-state {
            text-align: center;
            padding: 30px;
            color: #9ca3af;
            font-style: italic;
        }
    </style>
</head>

<body>
    <div class="print-container">
        <!-- Print Button (Hidden on print) -->
        <button onclick="window.print()" class="print-button no-print">
            🖨️ Cetak
        </button>

        <!-- Header -->
        <div class="header avoid-break">
            <div class="clinic-info">
                <div class="clinic-name">KLINIK BIDAN E-KOHORT</div>
                <div class="clinic-address">Jl. Kesehatan No. 123, Denpasar, Bali</div>
                <div class="clinic-address">Telp: (0361) 1234567 | Email: klinik@e-kohort.id</div>
            </div>
        </div>

        <div class="document-title">Rekam Medis Pasien</div>

        <!-- Patient Demographics -->
        <div class="section avoid-break">
            <div class="section-title">Data Demografis Pasien</div>
            <table class="info-table">
                <tr>
                    <td>No. Rekam Medis</td>
                    <td>:</td>
                    <td><strong>{{ $patient->no_rm }}</strong></td>
                </tr>
                <tr>
                    <td>NIK</td>
                    <td>:</td>
                    <td>{{ $patient->nik }}</td>
                </tr>
                <tr>
                    <td>No. KK</td>
                    <td>:</td>
                    <td>{{ $patient->no_kk ?? '-' }}</td>
                </tr>
                <tr>
                    <td>No. BPJS</td>
                    <td>:</td>
                    <td>{{ $patient->no_bpjs ?? '-' }}</td>
                </tr>
                <tr>
                    <td>Nama Lengkap</td>
                    <td>:</td>
                    <td><strong>{{ $patient->name }}</strong></td>
                </tr>
                <tr>
                    <td>Tempat, Tanggal Lahir</td>
                    <td>:</td>
                    <td>{{ $patient->pob }}, {{ \Carbon\Carbon::parse($patient->dob)->format('d F Y') }}
                        ({{ \Carbon\Carbon::parse($patient->dob)->age }} tahun)</td>
                </tr>
                <tr>
                    <td>Pendidikan</td>
                    <td>:</td>
                    <td>{{ $patient->education ?? '-' }}</td>
                </tr>
                <tr>
                    <td>Pekerjaan</td>
                    <td>:</td>
                    <td>{{ $patient->job ?? '-' }}</td>
                </tr>
                <tr>
                    <td>Golongan Darah</td>
                    <td>:</td>
                    <td>{{ $patient->blood_type }}</td>
                </tr>
                <tr>
                    <td>Alamat</td>
                    <td>:</td>
                    <td>{{ $patient->address }}</td>
                </tr>
                <tr>
                    <td>No. HP</td>
                    <td>:</td>
                    <td>{{ $patient->phone ?? '-' }}</td>
                </tr>
            </table>
        </div>

        <!-- Husband Information -->
        <div class="section avoid-break">
            <div class="section-title">Data Suami</div>
            <table class="info-table">
                <tr>
                    <td>Nama Suami</td>
                    <td>:</td>
                    <td>{{ $patient->husband_name ?? '-' }}</td>
                </tr>
                <tr>
                    <td>NIK Suami</td>
                    <td>:</td>
                    <td>{{ $patient->husband_nik ?? '-' }}</td>
                </tr>
                <tr>
                    <td>Pendidikan Suami</td>
                    <td>:</td>
                    <td>{{ $patient->husband_education ?? '-' }}</td>
                </tr>
                <tr>
                    <td>Pekerjaan Suami</td>
                    <td>:</td>
                    <td>{{ $patient->husband_job ?? '-' }}</td>
                </tr>
                <tr>
                    <td>Golongan Darah Suami</td>
                    <td>:</td>
                    <td>{{ $patient->husband_blood_type ?? '-' }}</td>
                </tr>
            </table>
        </div>

        <!-- Pregnancy History -->
        <div class="section">
            <div class="section-title">Riwayat Kehamilan</div>
            @if ($patient->pregnancies->count() > 0)
                <table class="data-table">
                    <thead>
                        <tr>
                            <th class="text-center">No</th>
                            <th>Gravida</th>
                            <th>HPHT</th>
                            <th>HPL</th>
                            <th class="text-center">BB Awal</th>
                            <th class="text-center">TB</th>
                            <th class="text-center">Jarak (Thn)</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Jml Kunjungan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($patient->pregnancies as $index => $pregnancy)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td>G{{ $pregnancy->gravida }}P{{ $pregnancy->para ?? 0 }}A{{ $pregnancy->abortus ?? 0 }}
                                </td>
                                <td>{{ $pregnancy->hpht ? \Carbon\Carbon::parse($pregnancy->hpht)->format('d/m/Y') : '-' }}
                                </td>
                                <td>{{ $pregnancy->hpl ? \Carbon\Carbon::parse($pregnancy->hpl)->format('d/m/Y') : '-' }}
                                </td>
                                <td class="text-center">{{ $pregnancy->weight_before ?? '-' }} kg</td>
                                <td class="text-center">{{ $pregnancy->height ?? '-' }} cm</td>
                                <td class="text-center">{{ $pregnancy->pregnancy_gap ?? '-' }}</td>
                                <td class="text-center">
                                    <span
                                        class="badge {{ $pregnancy->status == 'aktif' ? 'badge-active' : 'badge-complete' }}">
                                        {{ strtoupper($pregnancy->status) }}
                                    </span>
                                </td>
                                <td class="text-center">{{ $pregnancy->ancVisits->count() }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="empty-state">Belum ada riwayat kehamilan</div>
            @endif
        </div>

        <!-- ANC Visits (All Pregnancies) -->
        <div class="section">
            <div class="section-title">Riwayat Kunjungan ANC</div>
            @php
                $allVisits = $patient->pregnancies->flatMap->ancVisits->sortByDesc('visit_date');
            @endphp

            @if ($allVisits->count() > 0)
                <table class="data-table">
                    <thead>
                        <tr>
                            <th class="text-center">No</th>
                            <th>Tanggal</th>
                            <th class="text-center">Kode</th>
                            <th class="text-center">UK</th>
                            <th class="text-center">BB</th>
                            <th class="text-center">TD</th>
                            <th class="text-center">MAP</th>
                            <th class="text-center">LILA</th>
                            <th class="text-center">Hb</th>
                            <th class="text-center">Risiko</th>
                            <th>Keluhan/Diagnosa</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($allVisits as $index => $visit)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td>{{ \Carbon\Carbon::parse($visit->visit_date)->format('d/m/Y') }}</td>
                                <td class="text-center"><strong>{{ $visit->visit_code }}</strong></td>
                                <td class="text-center">{{ $visit->gestational_age }} mgg</td>
                                <td class="text-center">{{ $visit->weight }} kg</td>
                                <td class="text-center">{{ $visit->systolic }}/{{ $visit->diastolic }}</td>
                                <td class="text-center">{{ number_format($visit->map_score, 1) }}</td>
                                <td class="text-center">{{ $visit->lila }} cm</td>
                                <td class="text-center">{{ $visit->labResult->hb ?? '-' }}</td>
                                <td class="text-center">
                                    <span
                                        class="badge {{ $visit->risk_level == 'Tinggi' ? 'badge-high' : 'badge-normal' }}">
                                        {{ $visit->risk_level }}
                                    </span>
                                </td>
                                <td>{{ $visit->complaints ?: ($visit->diagnosis ?: '-') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="empty-state">Belum ada kunjungan ANC</div>
            @endif
        </div>

        <!-- Footer with Signature -->
        <div class="footer avoid-break">
            <div class="signature-box">
                <div>Denpasar, {{ \Carbon\Carbon::now()->format('d F Y') }}</div>
                <div style="margin-top: 5px;">Bidan Penanggung Jawab</div>
                <div class="signature-line">
                    <strong>{{ Auth::user()->name ?? '.........................' }}</strong>
                </div>
            </div>
        </div>

        <!-- Document Info -->
        <div class="no-print"
            style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #e5e7eb; text-align: center; color: #9ca3af; font-size: 9pt;">
            <p>Dokumen ini dicetak pada {{ \Carbon\Carbon::now()->format('d F Y, H:i') }} WIB</p>
            <p>Sistem E-Kohort Klinik v1.0.0</p>
        </div>
    </div>

    <script>
        // Auto-focus for print dialog on page load (optional)
        // window.onload = function() {
        //     window.print();
        // };
    </script>
</body>

</html>
