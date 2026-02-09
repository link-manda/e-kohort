<table>
    <thead>
        <tr>
            <th colspan="8" style="font-size: 16px; font-weight: bold; text-align: center;">RIWAYAT PERTUMBUHAN ANAK</th>
        </tr>
        <tr></tr>
        <tr>
            <td colspan="2" style="font-weight: bold;">Nama Anak:</td>
            <td colspan="2">{{ $child->name }}</td>
            <td colspan="2" style="font-weight: bold;">Jenis Kelamin:</td>
            <td colspan="2">{{ $child->gender == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
        </tr>
        <tr>
            <td colspan="2" style="font-weight: bold;">Tanggal Lahir:</td>
            <td colspan="2">{{ $child->dob->format('d F Y') }}</td>
            <td colspan="2" style="font-weight: bold;">Nama Orang Tua:</td>
            <td colspan="2">{{ $child->parent_display_name }}</td>
        </tr>
        <tr></tr>
        <tr>
            <th rowspan="2" style="font-weight: bold; text-align: center; vertical-align: middle; border: 1px solid black;">No</th>
            <th rowspan="2" style="font-weight: bold; text-align: center; vertical-align: middle; border: 1px solid black;">Tanggal Kunjungan</th>
            <th rowspan="2" style="font-weight: bold; text-align: center; vertical-align: middle; border: 1px solid black;">Usia</th>
            <th colspan="2" style="font-weight: bold; text-align: center; vertical-align: middle; border: 1px solid black;">Hasil Pengukuran</th>
            <th colspan="3" style="font-weight: bold; text-align: center; vertical-align: middle; border: 1px solid black;">Z-Score (SD)</th>
            <th rowspan="2" style="font-weight: bold; text-align: center; vertical-align: middle; border: 1px solid black;">Catatan</th>
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
            <td style="text-align: center; border: 1px solid black;">{{ $child->getAgeAtVisit($record->record_date) }}</td>
            <td style="text-align: center; border: 1px solid black;">{{ $record->weight }}</td>
            <td style="text-align: center; border: 1px solid black;">{{ $record->height }}</td>
            <td style="text-align: center; border: 1px solid black;">{{ round($record->z_score_bb_u, 2) }}</td>
            <td style="text-align: center; border: 1px solid black;">{{ round($record->z_score_tb_u, 2) }}</td>
            <td style="text-align: center; border: 1px solid black;">{{ round($record->z_score_bb_tb, 2) }}</td>
            <td style="border: 1px solid black;">{{ $record->notes }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
