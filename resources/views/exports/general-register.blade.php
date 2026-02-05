<!DOCTYPE html>
<html lang="id">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Register Poli Umum</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #000000;
            padding: 5px;
            vertical-align: middle;
            font-size: 11px;
            font-family: Arial, sans-serif;
        }

        th {
            text-align: center;
            font-weight: bold;
            background-color: #f2f2f2;
        }

        .center {
            text-align: center;
        }

        .nowrap {
            white-space: nowrap;
        }
    </style>
</head>

<body>
    <table>
        <thead>
            <!-- JUDUL -->
            <tr>
                <th colspan="12" style="font-size: 14px; padding: 10px;">
                    REGISTER RAWAT JALAN (POLI UMUM)<br>
                    PERIODE: {{ strtoupper($period ?? '-') }}
                </th>
            </tr>

            <!-- HEADER KOLOM -->
            <tr>
                <th style="width: 40px;">NO</th>
                <th style="width: 80px;">TANGGAL<br>KUNJUNGAN</th>
                <th style="width: 70px;">NO RM</th>
                <th style="width: 150px;">NAMA PASIEN</th>
                <th style="width: 100px;">NO KK</th>
                <th style="width: 200px;">ALAMAT</th>
                <th style="width: 40px;">UMUR<br>(Tahun)</th>
                <th style="width: 40px;">L/P</th>
                <th style="width: 150px;">KELUHAN</th>
                <th style="width: 150px;">DIAGNOSA</th>
                <th style="width: 150px;">TERAPI/TINDAKAN</th>
                <th style="width: 80px;">STATUS<br>PEMBAYARAN</th>
                <th style="width: 100px;">KETERANGAN</th>
            </tr>
        </thead>
        <tbody>
            @forelse($visits as $index => $visit)
                <tr>
                    <td class="center">{{ $index + 1 }}</td>
                    <td class="center nowrap">
                        {{ $visit->visit_date ? \Carbon\Carbon::parse($visit->visit_date)->format('d/m/Y H:i') : '-' }}
                    </td>
                    <td class="center">'{{ $visit->patient->no_rm ?? '-' }}</td>
                    <td>{{ $visit->patient->name ?? '-' }}</td>
                    <td class="center">'{{ $visit->patient->no_kk ?? '-' }}</td>
                    <td>{{ $visit->patient->address ?? '-' }}</td>
                    <td class="center">{{ $visit->patient->age ?? '-' }}</td>
                    <td class="center">{{ $visit->patient->gender == 'Laki-laki' ? 'L' : 'P' }}</td>
                    <td>{{ $visit->complaint ?? '-' }}</td>
                    <td>{{ $visit->diagnosis ?? '-' }}</td>
                    <td>{{ $visit->therapy ?? '-' }}</td>
                    <td class="center">{{ $visit->patient->payment_method ?? '-' }}</td>
                    <td>{{ $visit->notes ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="13" style="text-align: center; padding: 20px;">
                        Data Tidak Ditemukan
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>
