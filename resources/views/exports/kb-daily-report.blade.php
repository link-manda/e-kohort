<table>
    <thead>
        <tr>
            <th style="background-color: #4472C4; color: white; font-weight: bold;">NO</th>
            <th style="background-color: #4472C4; color: white; font-weight: bold;">TANGGAL</th>
            <th style="background-color: #4472C4; color: white; font-weight: bold;">NO RM</th>
            <th style="background-color: #4472C4; color: white; font-weight: bold;">NAMA IBU</th>
            <th style="background-color: #4472C4; color: white; font-weight: bold;">JENIS KUNJUNGAN</th>
            <th style="background-color: #4472C4; color: white; font-weight: bold;">METODE KB</th>
            <th style="background-color: #4472C4; color: white; font-weight: bold;">MEREK</th>
            <th style="background-color: #4472C4; color: white; font-weight: bold;">PEMBAYARAN</th>
            <th style="background-color: #4472C4; color: white; font-weight: bold;">BB (kg)</th>
            <th style="background-color: #4472C4; color: white; font-weight: bold;">TENSI</th>
            <th style="background-color: #4472C4; color: white; font-weight: bold;">DIAGNOSA</th>
            <th style="background-color: #4472C4; color: white; font-weight: bold;">BIDAN</th>
            <th style="background-color: #4472C4; color: white; font-weight: bold;">TGL KEMBALI</th>
            <th style="background-color: #4472C4; color: white; font-weight: bold;">CATATAN</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($visits as $index => $visit)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $visit->visit_date->format('d/m/Y H:i') }}</td>
                <td>{{ $visit->no_rm }}</td>
                <td>{{ $visit->patient->name }}</td>
                <td>{{ $visit->visit_type }}</td>
                <td>{{ $visit->kbMethod->name }}</td>
                <td>{{ $visit->contraception_brand ?? '-' }}</td>
                <td>{{ $visit->payment_type }}</td>
                <td>{{ $visit->weight ?? '-' }}</td>
                <td>{{ $visit->blood_pressure_systolic && $visit->blood_pressure_diastolic ? $visit->blood_pressure_systolic . '/' . $visit->blood_pressure_diastolic : '-' }}
                </td>
                <td>{{ $visit->diagnosis ?? '-' }}</td>
                <td>{{ $visit->midwife_name }}</td>
                <td>{{ $visit->next_visit_date ? \Carbon\Carbon::parse($visit->next_visit_date)->format('d/m/Y') : '-' }}
                </td>
                <td>{{ $visit->physical_exam_notes ?? ($visit->side_effects ?? '-') }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
