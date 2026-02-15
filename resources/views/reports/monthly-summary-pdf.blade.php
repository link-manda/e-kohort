<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Laporan Bulanan - {{ $data['period']['month_name'] }} {{ $data['period']['year'] }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            font-size: 18px;
            text-transform: uppercase;
        }
        .header p {
            margin: 2px 0;
            font-size: 12px;
        }
        .section-title {
            font-size: 14px;
            font-weight: bold;
            margin-top: 20px;
            margin-bottom: 10px;
            background-color: #f0f0f0;
            padding: 5px;
            border-left: 4px solid #333;
        }
        .metric-grid {
            width: 100%;
            margin-bottom: 15px;
        }
        .metric-card {
            border: 1px solid #ddd;
            padding: 10px;
            margin-bottom: 10px;
            background-color: #fff;
        }
        .metric-title {
            font-size: 11px;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .metric-value {
            font-size: 16px;
            font-weight: bold;
            color: #333;
            margin-top: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 6px;
            text-align: left;
        }
        th {
            background-color: #f9f9f9;
            font-weight: bold;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            font-size: 10px;
            color: #999;
            text-align: right;
            border-top: 1px solid #eee;
            padding-top: 5px;
        }
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Laporan Ringkasan Bulanan</h1>
        <p>E-Kohort Klinik</p>
        <p>Periode: <strong>{{ $data['period']['month_name'] }} {{ $data['period']['year'] }}</strong></p>
    </div>

    <!-- Executive Summary -->
    <div class="section-title">Ringkasan Eksekutif</div>
    <table class="metric-grid">
        <tr>
            <td width="25%">
                <div class="metric-title">Pasien Baru</div>
                <div class="metric-value">{{ $data['patient_demographics']['new_patients_total'] + $data['patient_demographics']['new_children'] }}</div>
            </td>
            <td width="25%">
                <div class="metric-title">Kunjungan ANC</div>
                <div class="metric-value">{{ $data['total_visits'] }}</div>
            </td>
            <td width="25%">
                <div class="metric-title">Imunisasi</div>
                <div class="metric-value">{{ $data['immunization']['total_actions'] }}</div>
            </td>
            <td width="25%">
                <div class="metric-title">Risiko Tinggi</div>
                <div class="metric-value">{{ $data['high_risk_count'] }}</div>
            </td>
        </tr>
    </table>

    <!-- Maternal Health -->
    <div class="section-title">Kesehatan Ibu & Anak</div>

    <h4>1. Antenatal Care (ANC)</h4>
    <table class="table">
        <thead>
            <tr>
                <th colspan="8" class="text-center">Kunjungan per Kode</th>
            </tr>
            <tr>
                @foreach (['K1', 'K2', 'K3', 'K4', 'K5', 'K6', 'K7', 'K8'] as $code)
                    <th class="text-center">{{ $code }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            <tr>
                @foreach (['K1', 'K2', 'K3', 'K4', 'K5', 'K6', 'K7', 'K8'] as $code)
                    <td class="text-center">{{ $data['visits_by_code'][strtolower($code)] ?? 0 }}</td>
                @endforeach
            </tr>
        </tbody>
    </table>

    <table class="table">
        <thead>
            <tr>
                <th>Indikator Risiko</th>
                <th>Jumlah</th>
                <th>Skrining Triple Eliminasi</th>
                <th>Reaktif</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>MAP Tinggi / Ekstrem</td>
                <td>{{ $data['high_risk_count'] }} / {{ $data['extreme_risk_count'] }}</td>
                <td>HIV</td>
                <td>{{ $data['triple_eliminasi']['hiv_reactive'] }}</td>
            </tr>
            <tr>
                <td>KEK (LILA < 23.5)</td>
                <td>{{ $data['kek_count'] }}</td>
                <td>Sifilis</td>
                <td>{{ $data['triple_eliminasi']['syphilis_reactive'] }}</td>
            </tr>
            <tr>
                <td>Anemia (Hb < 11)</td>
                <td>{{ $data['anemia_count'] }}</td>
                <td>HBsAg</td>
                <td>{{ $data['triple_eliminasi']['hbsag_reactive'] }}</td>
            </tr>
        </tbody>
    </table>

    <h4>2. Persalinan & Nifas</h4>
    <table class="table">
        <thead>
            <tr>
                <th colspan="2">Persalinan</th>
                <th colspan="3">Kunjungan Nifas (KF)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Total Persalinan</td>
                <td class="text-right">{{ $data['delivery']['total_records'] }}</td>
                <td>KF1</td>
                <td class="text-right">{{ $data['postnatal']['kf1'] }}</td>
            </tr>
            <tr>
                <td>Normal / SC</td>
                <td class="text-right">{{ $data['delivery']['normal'] }} / {{ $data['delivery']['sc'] }}</td>
                <td>KF2</td>
                <td class="text-right">{{ $data['postnatal']['kf2'] }}</td>
            </tr>
            <tr>
                <td>Lahir Hidup / Mati</td>
                <td class="text-right">{{ $data['delivery']['live_births'] }} / {{ $data['delivery']['stillbirths'] }}</td>
                <td>KF3</td>
                <td class="text-right">{{ $data['postnatal']['kf3'] }}</td>
            </tr>
        </tbody>
    </table>

    <!-- Child Health -->
    <div class="section-title">Kesehatan Balita</div>

    <table class="table">
        <tr>
            <td width="50%" valign="top">
                <h4>Imunisasi</h4>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Vaksin</th>
                            <th class="text-right">Jml</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($data['immunization']['by_vaccine'] as $vaccine => $count)
                            <tr>
                                <td>{{ $vaccine }}</td>
                                <td class="text-right">{{ $count }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="2" class="text-center">Belum ada data</td></tr>
                        @endforelse
                        <tr>
                            <td><strong>Total Tindakan</strong></td>
                            <td class="text-right"><strong>{{ $data['immunization']['total_actions'] }}</strong></td>
                        </tr>
                    </tbody>
                </table>
            </td>
            <td width="50%" valign="top">
                <h4>Pertumbuhan & Gizi</h4>
                <table class="table">
                    <tr>
                        <td>Total Penimbangan</td>
                        <td class="text-right">{{ $data['child_growth']['total_measurements'] }}</td>
                    </tr>
                    <tr>
                        <td>Dapat Vitamin A</td>
                        <td class="text-right">{{ $data['child_growth']['vitamin_a'] }}</td>
                    </tr>
                    <tr>
                        <th colspan="2">Status Gizi (WHO)</th>
                    </tr>
                    <tr>
                        <td>Normal</td>
                        <td class="text-right">{{ $data['child_growth']['nutrition_summary']->normal ?? 0 }}</td>
                    </tr>
                    <tr>
                        <td>Wasting (Kurus)</td>
                        <td class="text-right">{{ $data['child_growth']['nutrition_summary']->wasting ?? 0 }}</td>
                    </tr>
                    <tr>
                        <td>Stunting</td>
                        <td class="text-right">{{ $data['child_growth']['nutrition_summary']->stunting ?? 0 }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <div class="page-break"></div>

    <!-- KB & General Visits -->
    <div class="section-title">KB & Poli Umum</div>

    <table class="table">
        <tr>
            <td width="50%" valign="top">
                <h4>Keluarga Berencana</h4>
                <table class="table">
                    <tr>
                        <td>Akseptor Baru</td>
                        <td class="text-right">{{ $data['kb']['new_acceptors'] }}</td>
                    </tr>
                    <tr>
                        <th colspan="2">Metode KB</th>
                    </tr>
                    @forelse ($data['kb']['by_method'] as $method => $count)
                        <tr>
                            <td>{{ $method }}</td>
                            <td class="text-right">{{ $count }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="2" class="text-center">Belum ada data</td></tr>
                    @endforelse
                </table>
            </td>
            <td width="50%" valign="top">
                <h4>Poli Umum</h4>
                <table class="table">
                    <tr>
                        <td>Pasien Dewasa</td>
                        <td class="text-right">{{ $data['general_visits']['adult_patients'] }}</td>
                    </tr>
                    <tr>
                        <td>Pasien Anak</td>
                        <td class="text-right">{{ $data['general_visits']['child_patients'] }}</td>
                    </tr>
                    <tr>
                        <th colspan="2">Top 5 Diagnosa</th>
                    </tr>
                    @forelse (array_slice($data['general_visits']['top_diagnoses'], 0, 5) as $diagnosis)
                        <tr>
                            <td>{{ $diagnosis['code'] }} - {{ $diagnosis['description'] }}</td>
                            <td class="text-right">{{ $diagnosis['count'] }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="2" class="text-center">Belum ada data diagnosa</td></tr>
                    @endforelse
                </table>
            </td>
        </tr>
    </table>

    <!-- Other Services -->
    <div class="section-title">Layanan & Intervensi Lainnya</div>

    <table class="table">
        <thead>
            <tr>
                <th colspan="5" class="text-center">Imunisasi TT (Ibu Hamil)</th>
            </tr>
            <tr>
                @foreach (['TT1', 'TT2', 'TT3', 'TT4', 'TT5'] as $tt)
                    <th class="text-center">{{ $tt }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            <tr>
                @foreach (['tt1', 'tt2', 'tt3', 'tt4', 'tt5'] as $tt)
                    <td class="text-center">{{ $data['tt_immunization'][$tt] ?? 0 }}</td>
                @endforeach
            </tr>
        </tbody>
    </table>

    <table class="table">
        <thead>
            <tr>
                <th>Layanan ANC Lainnya</th>
                <th class="text-right">Jumlah</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Pemeriksaan USG</td>
                <td class="text-right">{{ $data['usg_check'] }}</td>
            </tr>
            <tr>
                <td>Konseling</td>
                <td class="text-right">{{ $data['counseling_check'] }}</td>
            </tr>
            <tr>
                <td>ANC Lengkap (12T)</td>
                <td class="text-right">{{ $data['anc_12t'] }}</td>
            </tr>
            <tr>
                <td>Rujukan Keluar</td>
                <td class="text-right">{{ $data['referrals'] }}</td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        Dicetak pada: {{ date('d M Y H:i') }} | E-Kohort Klinik System
    </div>
</body>
</html>
