<table>
    <thead>
        <tr>
            <th colspan="12" style="font-weight: bold; text-align: center; font-size: 14px;">REGISTER RAWAT JALAN (POLI UMUM)</th>
        </tr>
        <tr>
            <th colspan="12" style="text-align: center;">Periode: {{ $period }}</th>
        </tr>
        <tr>
            <th style="width: 5px;">No</th>
            <th style="width: 15px;">Tanggal</th>
            <th style="width: 15px;">No RM</th>
            <th style="width: 25px;">Nama Pasien</th>
            <th style="width: 30px;">KK / Alamat</th>
            <th style="width: 8px;">Umur</th>
            <th style="width: 5px;">L/P</th>
            <th style="width: 25px;">Keluhan</th>
            <th style="width: 25px;">Diagnosa</th>
            <th style="width: 25px;">Terapi</th>
            <th style="width: 15px;">Status</th>
            <th style="width: 10px;">Ket</th>
        </tr>
    </thead>
    <tbody>
        @foreach($visits as $index => $visit)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $visit->visit_date->format('d/m/Y H:i') }}</td>
                <td>{{ $visit->patient->no_rm }}</td>
                <td>{{ $visit->patient->name }}</td>
                <td>{{ $visit->patient->no_kk ? $visit->patient->no_kk . ' / ' : '' }}{{ $visit->patient->address }}</td>
                <td>{{ $visit->patient->age }}</td>
                <td>{{ $visit->patient->gender }}</td>
                <td>{{ $visit->complaint }}</td>
                <td>{{ $visit->diagnosis }}</td>
                <td>{{ $visit->therapy }}</td>
                <td>{{ $visit->status }}</td>
                <td>{{ $visit->payment_method }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
