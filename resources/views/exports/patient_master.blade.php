<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Data Pasien</title>
</head>
<body>
    <table style="border-collapse: collapse; width: 100%;">
        <thead>
            <tr>
                <th style="background-color: #f0f0f0;">NO</th>
                <th style="background-color: #f0f0f0;">NO RM</th>
                <th style="background-color: #f0f0f0;">NIK</th>
                <th style="background-color: #f0f0f0;">NO KK</th>
                <th style="background-color: #f0f0f0;">NO BPJS</th>
                <th style="background-color: #ffc0cb;">NAMA LENGKAP</th>
                <th style="background-color: #ffc0cb;">TEMPAT LAHIR</th>
                <th style="background-color: #ffc0cb;">TGL LAHIR</th>
                <th style="background-color: #ffc0cb;">UMUR (THN)</th>
                <th style="background-color: #ffc0cb;">PENDIDIKAN</th>
                <th style="background-color: #ffc0cb;">PEKERJAAN</th>
                <th style="background-color: #ffc0cb;">GOL. DARAH</th>
                <th style="background-color: #ffe4b5;">ALAMAT LENGKAP</th>
                <th style="background-color: #ffe4b5;">NO HP</th>
                <th style="background-color: #add8e6;">NAMA SUAMI</th>
                <th style="background-color: #add8e6;">NIK SUAMI</th>
                <th style="background-color: #add8e6;">PENDIDIKAN</th>
                <th style="background-color: #add8e6;">PEKERJAAN</th>
                <th style="background-color: #add8e6;">GOL. DARAH</th>
                <th style="background-color: #98fb98;">STATUS KEHAMILAN</th>
                <th style="background-color: #98fb98;">TGL REGISTRASI</th>
            </tr>
        </thead>
        <tbody>
            @foreach($patients as $index => $patient)
            @php
                $activePregnancy = $patient->pregnancies->where('status', 'aktif')->first();
                $latestPregnancy = $patient->pregnancies->first();

                $statusText = 'BELUM ADA';
                $statusColor = '#721C24';
                $statusBg = '#F8D7DA';

                if ($activePregnancy) {
                    $statusText = 'AKTIF';
                    $statusColor = '#155724';
                    $statusBg = '#D4EDDA';
                } elseif ($latestPregnancy) {
                    $statusText = strtoupper($latestPregnancy->status);
                    $statusColor = '#856404';
                    $statusBg = '#FFF3CD';
                }
            @endphp
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $patient->no_rm }}</td>
                <td>{{ $patient->nik ? "'".$patient->nik : '-' }}</td>
                <td>{{ $patient->no_kk ? "'".$patient->no_kk : '-' }}</td>
                <td>{{ $patient->no_bpjs ? "'".$patient->no_bpjs : '-' }}</td>
                <td>{{ $patient->name }}</td>
                <td>{{ $patient->pob ?? '-' }}</td>
                <td>{{ $patient->dob ? $patient->dob->format('d/m/Y') : '-' }}</td>
                <td>{{ $patient->dob ? $patient->dob->age : '-' }}</td>
                <td>{{ $patient->education ?? '-' }}</td>
                <td>{{ $patient->job ?? '-' }}</td>
                <td>{{ $patient->blood_type ?? '-' }}</td>
                <td>{{ $patient->address ?? '-' }}</td>
                <td>{{ $patient->phone ? "'".$patient->phone : '-' }}</td>
                <td>{{ $patient->husband_name ?? '-' }}</td>
                <td>{{ $patient->husband_nik ? "'".$patient->husband_nik : '-' }}</td>
                <td>{{ $patient->husband_education ?? '-' }}</td>
                <td>{{ $patient->husband_job ?? '-' }}</td>
                <td>{{ $patient->husband_blood_type ?? '-' }}</td>
                <td>
                    <span style="background-color: {{ $statusBg }}; color: {{ $statusColor }}; padding: 3px 8px; border-radius: 3px; font-weight: bold;">
                        {{ $statusText }}
                    </span>
                </td>
                <td>{{ $patient->created_at->format('d/m/Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
