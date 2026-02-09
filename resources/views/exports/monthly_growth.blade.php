<table>
    <thead>
        <tr>
            <th colspan="12" style="font-size: 16px; font-weight: bold; text-align: center;">LAPORAN DATA PERTUMBUHAN ANAK (GIZI)</th>
        </tr>
        <tr>
            <th colspan="12" style="font-size: 12px; font-weight: bold; text-align: center;">Bulan: {{ $dateFormatted }}</th>
        </tr>
        <tr></tr>
        <tr>
            <th rowspan="2" style="font-weight: bold; text-align: center; vertical-align: middle; border: 1px solid black;">No</th>
            <th rowspan="2" style="font-weight: bold; text-align: center; vertical-align: middle; border: 1px solid black;">Tanggal Catat</th>
            <th rowspan="2" style="font-weight: bold; text-align: center; vertical-align: middle; border: 1px solid black;">Nama Anak</th>
            <th rowspan="2" style="font-weight: bold; text-align: center; vertical-align: middle; border: 1px solid black;">NIK</th>
            <th rowspan="2" style="font-weight: bold; text-align: center; vertical-align: middle; border: 1px solid black;">Jenis Kelamin</th>
            <th rowspan="2" style="font-weight: bold; text-align: center; vertical-align: middle; border: 1px solid black;">Tanggal Lahir</th>
            <th rowspan="2" style="font-weight: bold; text-align: center; vertical-align: middle; border: 1px solid black;">Usia saat Kunjungan</th>
            <th colspan="2" style="font-weight: bold; text-align: center; vertical-align: middle; border: 1px solid black;">Hasil Pengukuran</th>
            <th colspan="3" style="font-weight: bold; text-align: center; vertical-align: middle; border: 1px solid black;">Status Gizi (Z-Score)</th>
        </tr>
        <tr>
            <th style="font-weight: bold; text-align: center; border: 1px solid black;">BB (kg)</th>
            <th style="font-weight: bold; text-align: center; border: 1px solid black;">TB (cm)</th>
            <th style="font-weight: bold; text-align: center; border: 1px solid black;">BB/U</th>
            <th style="font-weight: bold; text-align: center; border: 1px solid black;">TB/U</th>
            <th style="font-weight: bold; text-align: center; border: 1px solid black;">BB/TB</th>
        </tr>
    </thead>
    <tbody>
        @foreach($records as $index => $record)
        <tr>
            <td style="text-align: center; border: 1px solid black;">{{ $index + 1 }}</td>
            <td style="text-align: center; border: 1px solid black;">{{ $record->record_date->format('d/m/Y') }}</td>
            <td style="border: 1px solid black;">{{ $record->child->name }}</td>
            <td style="text-align: center; border: 1px solid black;">{{ $record->child->nik ?? '-' }}</td>
            <td style="text-align: center; border: 1px solid black;">{{ $record->child->gender }}</td>
            <td style="text-align: center; border: 1px solid black;">{{ $record->child->dob->format('d/m/Y') }}</td>
            <td style="text-align: center; border: 1px solid black;">{{ $record->child->getAgeAtVisit($record->record_date) }}</td>
            <td style="text-align: center; border: 1px solid black;">{{ $record->weight }}</td>
            <td style="text-align: center; border: 1px solid black;">{{ $record->height }}</td>

            <!-- Status BB/U -->
            <td style="text-align: center; border: 1px solid black;">
                @if($record->z_score_bb_u < -3) Buruk (Severely Wasted)
                @elseif($record->z_score_bb_u < -2) Kurang (Underweight)
                @elseif($record->z_score_bb_u > 2) Lebih (Overweight)
                @else Normal
                @endif
                ({{ round($record->z_score_bb_u, 2) }} SD)
            </td>

            <!-- Status TB/U -->
            <td style="text-align: center; border: 1px solid black;">
                @if($record->z_score_tb_u < -3) Sangat Pendek (Severely Stunted)
                @elseif($record->z_score_tb_u < -2) Pendek (Stunted)
                @elseif($record->z_score_tb_u > 2) Tinggi
                @else Normal
                @endif
                ({{ round($record->z_score_tb_u, 2) }} SD)
            </td>

            <!-- Status BB/TB -->
            <td style="text-align: center; border: 1px solid black;">
                @if($record->z_score_bb_tb < -3) Gizi Buruk (Severely Wasted)
                @elseif($record->z_score_bb_tb < -2) Gizi Kurang (Wasted)
                @elseif($record->z_score_bb_tb > 2) Gizi Lebih (Overweight)
                @elseif($record->z_score_bb_tb > 3) Obesitas
                @else Gizi Baik (Normal)
                @endif
                ({{ round($record->z_score_bb_tb, 2) }} SD)
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
