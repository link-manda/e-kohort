<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<table border="1" cellpadding="5" cellspacing="0" style="border-collapse: collapse;">
    <thead>
        <tr>
            <th style="background-color: #4472C4; color: white; font-weight: bold;">No</th>
            <th style="background-color: #4472C4; color: white; font-weight: bold;">No RM</th>
            <th style="background-color: #4472C4; color: white; font-weight: bold;">NIK</th>
            <th style="background-color: #4472C4; color: white; font-weight: bold;">No KK</th>
            <th style="background-color: #4472C4; color: white; font-weight: bold;">No BPJS</th>
            <th style="background-color: #4472C4; color: white; font-weight: bold;">Nama Lengkap</th>
            <th style="background-color: #4472C4; color: white; font-weight: bold;">Tempat Lahir</th>
            <th style="background-color: #4472C4; color: white; font-weight: bold;">Tanggal Lahir</th>
            <th style="background-color: #4472C4; color: white; font-weight: bold;">Umur (Thn)</th>
            <th style="background-color: #4472C4; color: white; font-weight: bold;">Pendidikan</th>
            <th style="background-color: #4472C4; color: white; font-weight: bold;">Pekerjaan</th>
            <th style="background-color: #4472C4; color: white; font-weight: bold;">Golongan Darah</th>
            <th style="background-color: #4472C4; color: white; font-weight: bold;">Alamat</th>
            <th style="background-color: #4472C4; color: white; font-weight: bold;">No HP</th>
            <th style="background-color: #4472C4; color: white; font-weight: bold;">Nama Suami</th>
            <th style="background-color: #4472C4; color: white; font-weight: bold;">NIK Suami</th>
            <th style="background-color: #4472C4; color: white; font-weight: bold;">Pendidikan Suami</th>
            <th style="background-color: #4472C4; color: white; font-weight: bold;">Pekerjaan Suami</th>
            <th style="background-color: #4472C4; color: white; font-weight: bold;">Golda Suami</th>
            <th style="background-color: #4472C4; color: white; font-weight: bold;">Status Kehamilan</th>
            <th style="background-color: #4472C4; color: white; font-weight: bold;">Tgl Registrasi</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($patients as $index => $patient)
            @php
                $activePregnancy = $patient->pregnancies->where('status', 'aktif')->first();
                $latestPregnancy = $patient->pregnancies->first();
            @endphp
            <tr>
                <td style="text-align: center;">{{ $index + 1 }}</td>
                <td>{{ $patient->no_rm }}</td>
                <td>'{{ $patient->nik }}</td> <!-- Tanda kutip untuk prevent scientific notation -->
                <td>'{{ $patient->no_kk ?? '-' }}</td>
                <td>'{{ $patient->no_bpjs ?? '-' }}</td>
                <td>{{ $patient->name }}</td>
                <td>{{ $patient->pob }}</td>
                <td>{{ $patient->dob }}</td>
                <td style="text-align: center;">{{ \Carbon\Carbon::parse($patient->dob)->age }}</td>
                <td>{{ $patient->education ?? '-' }}</td>
                <td>{{ $patient->job ?? '-' }}</td>
                <td style="text-align: center;">{{ $patient->blood_type ?? '-' }}</td>
                <td>{{ $patient->address }}</td>
                <td>'{{ $patient->phone ?? '-' }}</td>
                <td>{{ $patient->husband_name ?? '-' }}</td>
                <td>'{{ $patient->husband_nik ?? '-' }}</td>
                <td>{{ $patient->husband_education ?? '-' }}</td>
                <td>{{ $patient->husband_job ?? '-' }}</td>
                <td style="text-align: center;">{{ $patient->husband_blood_type ?? '-' }}</td>
                <td style="text-align: center;">
                    @if ($activePregnancy)
                        <span
                            style="background-color: #D4EDDA; color: #155724; padding: 3px 8px; border-radius: 3px; font-weight: bold;">AKTIF</span>
                    @elseif ($latestPregnancy)
                        <span
                            style="background-color: #FFF3CD; color: #856404; padding: 3px 8px; border-radius: 3px;">{{ strtoupper($latestPregnancy->status) }}</span>
                    @else
                        <span
                            style="background-color: #F8D7DA; color: #721C24; padding: 3px 8px; border-radius: 3px;">BELUM
                            ADA</span>
                    @endif
                </td>
                <td style="text-align: center;">{{ $patient->created_at->format('d/m/Y') }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
