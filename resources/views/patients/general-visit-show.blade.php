<x-dashboard-layout>
    <div class="mb-6">
        <!-- Breadcrumb -->
        <x-breadcrumb :items="[
            ['label' => 'Poli Umum', 'url' => route('general-visits.index')],
            ['label' => $patient->name, 'url' => route('patients.show', $patient)],
            ['label' => 'Detail Kunjungan Poli Umum']
        ]" />

        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
            <div class="flex items-center gap-4">
                <a href="{{ route('general-visits.index') }}" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                    <x-heroicon-o-arrow-left class="w-6 h-6 text-gray-600" />
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Detail Kunjungan Poli Umum</h1>
                    <p class="text-sm text-gray-500 mt-1">
                        {{ $generalVisit->visit_date?->locale('id')->isoFormat('dddd, D MMMM YYYY • HH:mm') }} WIB
                        @if($generalVisit->is_emergency)
                            <span class="ml-2 inline-flex items-center px-2 py-0.5 bg-red-100 text-red-800 text-xs font-bold rounded-full">🚨 DARURAT</span>
                        @endif
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-2 flex-wrap">
                <a href="{{ route('patients.show', $patient) }}"
                   class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-lg transition-colors text-sm">
                    <x-heroicon-o-arrow-left class="w-4 h-4" />
                    Kembali ke Profil Pasien
                </a>
                <a href="{{ route('general-visits.create', ['patient_id' => $patient->id]) }}"
                   class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-colors text-sm shadow-sm">
                    <x-heroicon-o-plus class="w-4 h-4" />
                    Kunjungan Baru
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column (2/3) - Main Content -->
            <div class="lg:col-span-2 space-y-6">

                <!-- SUBJECTIVE Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <span class="inline-flex items-center justify-center w-8 h-8 bg-blue-100 text-blue-700 rounded-lg text-sm font-bold">S</span>
                        Subjective (Anamnesa)
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div class="md:col-span-2">
                            <p class="text-xs font-semibold text-gray-500 uppercase mb-1">Keluhan Utama</p>
                            <p class="text-gray-900">{{ $generalVisit->complaint ?? '-' }}</p>
                        </div>
                        @if($generalVisit->allergies)
                            <div>
                                <p class="text-xs font-semibold text-gray-500 uppercase mb-1">Alergi</p>
                                <p class="text-red-600 font-medium">⚠️ {{ $generalVisit->allergies }}</p>
                            </div>
                        @endif
                        @if($generalVisit->medical_history)
                            <div>
                                <p class="text-xs font-semibold text-gray-500 uppercase mb-1">Riwayat Penyakit</p>
                                <p class="text-gray-700">{{ $generalVisit->medical_history }}</p>
                            </div>
                        @endif
                        @if($generalVisit->consciousness)
                            <div>
                                <p class="text-xs font-semibold text-gray-500 uppercase mb-1">Tingkat Kesadaran</p>
                                <p class="text-gray-900 font-medium">{{ $generalVisit->consciousness }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- OBJECTIVE Card - Vital Signs -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <span class="inline-flex items-center justify-center w-8 h-8 bg-green-100 text-green-700 rounded-lg text-sm font-bold">O</span>
                        Objective (Pemeriksaan)
                    </h3>

                    <p class="text-xs font-semibold text-gray-500 uppercase mb-3">Tanda Vital</p>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-6">
                        @if($generalVisit->systolic && $generalVisit->diastolic)
                            @php
                                $bpHigh = $generalVisit->systolic >= 140 || $generalVisit->diastolic >= 90;
                                $bpPre = !$bpHigh && ($generalVisit->systolic >= 120 || $generalVisit->diastolic >= 80);
                            @endphp
                            <div class="rounded-lg p-4 text-center {{ $bpHigh ? 'bg-red-50 border border-red-200' : ($bpPre ? 'bg-yellow-50 border border-yellow-200' : 'bg-green-50 border border-green-200') }}">
                                <p class="text-xs text-gray-500 mb-1">🩺 Tekanan Darah</p>
                                <p class="text-2xl font-bold {{ $bpHigh ? 'text-red-600' : ($bpPre ? 'text-yellow-600' : 'text-green-600') }}">
                                    {{ $generalVisit->systolic }}/{{ $generalVisit->diastolic }}
                                </p>
                                <p class="text-xs text-gray-500">mmHg</p>
                                @if($bpHigh)
                                    <p class="text-xs text-red-600 font-semibold mt-1">⚠️ Hipertensi</p>
                                @elseif($bpPre)
                                    <p class="text-xs text-yellow-600 font-semibold mt-1">⚠️ Pra-Hipertensi</p>
                                @else
                                    <p class="text-xs text-green-600 font-semibold mt-1">✓ Normal</p>
                                @endif
                            </div>
                        @endif

                        <div class="rounded-lg p-4 text-center {{ $generalVisit->temperature >= 38 ? 'bg-red-50 border border-red-200' : ($generalVisit->temperature > 37.5 ? 'bg-yellow-50 border border-yellow-200' : 'bg-green-50 border border-green-200') }}">
                            <p class="text-xs text-gray-500 mb-1">🌡️ Suhu Tubuh</p>
                            <p class="text-2xl font-bold {{ $generalVisit->temperature >= 38 ? 'text-red-600' : ($generalVisit->temperature > 37.5 ? 'text-yellow-600' : 'text-green-600') }}">
                                {{ $generalVisit->temperature ? number_format($generalVisit->temperature, 1) : '-' }}
                            </p>
                            <p class="text-xs text-gray-500">°C</p>
                            @if($generalVisit->temperature >= 38)
                                <p class="text-xs text-red-600 font-semibold mt-1">⚠️ Demam</p>
                            @elseif($generalVisit->temperature)
                                <p class="text-xs text-green-600 font-semibold mt-1">✓ Normal</p>
                            @endif
                        </div>

                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 text-center">
                            <p class="text-xs text-gray-500 mb-1">⚖️ Berat Badan</p>
                            <p class="text-2xl font-bold text-blue-600">{{ $generalVisit->weight ? number_format($generalVisit->weight, 1) : '-' }}</p>
                            <p class="text-xs text-gray-500">kg</p>
                        </div>
                        <div class="bg-indigo-50 border border-indigo-200 rounded-lg p-4 text-center">
                            <p class="text-xs text-gray-500 mb-1">📏 Tinggi Badan</p>
                            <p class="text-2xl font-bold text-indigo-600">{{ $generalVisit->height ? number_format($generalVisit->height, 1) : '-' }}</p>
                            <p class="text-xs text-gray-500">cm</p>
                        </div>
                        @if($generalVisit->heart_rate)
                            <div class="bg-pink-50 border border-pink-200 rounded-lg p-4 text-center">
                                <p class="text-xs text-gray-500 mb-1">❤️ Nadi</p>
                                <p class="text-2xl font-bold text-pink-600">{{ $generalVisit->heart_rate }}</p>
                                <p class="text-xs text-gray-500">bpm</p>
                            </div>
                        @endif
                        @if($generalVisit->respiratory_rate)
                            <div class="bg-teal-50 border border-teal-200 rounded-lg p-4 text-center">
                                <p class="text-xs text-gray-500 mb-1">💨 Nafas</p>
                                <p class="text-2xl font-bold text-teal-600">{{ $generalVisit->respiratory_rate }}</p>
                                <p class="text-xs text-gray-500">/menit</p>
                            </div>
                        @endif
                        @if($generalVisit->bmi)
                            @php
                                $bmiColor = $generalVisit->bmi < 18.5 ? 'yellow' : ($generalVisit->bmi < 25 ? 'green' : ($generalVisit->bmi < 30 ? 'orange' : 'red'));
                                $bmiLabel = $generalVisit->bmi < 18.5 ? 'Underweight' : ($generalVisit->bmi < 25 ? 'Normal' : ($generalVisit->bmi < 30 ? 'Overweight' : 'Obesitas'));
                            @endphp
                            <div class="bg-{{ $bmiColor }}-50 border border-{{ $bmiColor }}-200 rounded-lg p-4 text-center">
                                <p class="text-xs text-gray-500 mb-1">📊 BMI</p>
                                <p class="text-2xl font-bold text-{{ $bmiColor }}-600">{{ number_format($generalVisit->bmi, 1) }}</p>
                                <p class="text-xs text-{{ $bmiColor }}-600 font-semibold mt-1">{{ $bmiLabel }}</p>
                            </div>
                        @endif
                    </div>

                    @if($generalVisit->physical_exam)
                        <p class="text-xs font-semibold text-gray-500 uppercase mb-2 mt-4">Pemeriksaan Fisik</p>
                        <p class="text-sm text-gray-700 bg-gray-50 rounded-lg p-3 border border-gray-200">{{ $generalVisit->physical_exam }}</p>
                    @endif
                </div>

                <!-- ASSESSMENT -->
                @if($generalVisit->diagnosis || $generalVisit->icd10_code)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                            <span class="inline-flex items-center justify-center w-8 h-8 bg-purple-100 text-purple-700 rounded-lg text-sm font-bold">A</span>
                            Assessment (Diagnosa)
                        </h3>
                        <p class="text-gray-900 font-medium text-base">{{ $generalVisit->diagnosis ?? '-' }}</p>
                        @if($generalVisit->icd10_code)
                            <p class="mt-2 text-gray-500 text-sm">
                                Kode ICD-10: <span class="font-mono font-bold text-purple-700 bg-purple-50 px-2 py-0.5 rounded">{{ $generalVisit->icd10_code }}</span>
                            </p>
                        @endif
                    </div>
                @endif

                <!-- PLAN -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <span class="inline-flex items-center justify-center w-8 h-8 bg-orange-100 text-orange-700 rounded-lg text-sm font-bold">P</span>
                        Plan (Tindakan & Resep)
                    </h3>
                    @if($generalVisit->therapy)
                        <div class="mb-4">
                            <p class="text-xs font-semibold text-gray-500 uppercase mb-2">Terapi/Tindakan</p>
                            <p class="text-sm text-gray-700 bg-orange-50 rounded-lg p-3 border border-orange-200">{{ $generalVisit->therapy }}</p>
                        </div>
                    @endif
                    @if($generalVisit->prescriptions && $generalVisit->prescriptions->count() > 0)
                        <p class="text-xs font-semibold text-gray-500 uppercase mb-3">💊 Resep Obat ({{ $generalVisit->prescriptions->count() }} item)</p>
                        <div class="bg-green-50 border border-green-200 rounded-lg overflow-hidden">
                            <div class="overflow-x-auto">
                                <table class="w-full text-xs">
                                    <thead class="bg-green-100">
                                        <tr>
                                            <th class="px-4 py-2 text-left text-gray-700 font-semibold">Nama Obat</th>
                                            <th class="px-4 py-2 text-center text-gray-700 font-semibold">Qty</th>
                                            <th class="px-4 py-2 text-left text-gray-700 font-semibold">Dosis</th>
                                            <th class="px-4 py-2 text-left text-gray-700 font-semibold">Signa</th>
                                            <th class="px-4 py-2 text-right text-gray-700 font-semibold">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-green-200">
                                        @foreach($generalVisit->prescriptions as $rx)
                                            <tr class="hover:bg-green-100/50">
                                                <td class="px-4 py-3 font-semibold text-green-900">{{ $rx->medicine_name }}</td>
                                                <td class="px-4 py-3 text-center text-gray-700">{{ $rx->quantity }}</td>
                                                <td class="px-4 py-3 text-gray-700">{{ $rx->dosage ?? '-' }}</td>
                                                <td class="px-4 py-3 text-gray-700">{{ $rx->signa ?? '-' }}</td>
                                                <td class="px-4 py-3 text-right font-bold text-green-700">Rp {{ number_format($rx->total_price, 0, ',', '.') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="px-4 py-3 bg-green-100 flex justify-between items-center border-t border-green-300">
                                <span class="text-sm font-bold text-gray-700">Total Biaya Obat:</span>
                                <span class="text-base font-bold text-green-700">Rp {{ number_format($generalVisit->prescriptions->sum('total_price'), 0, ',', '.') }}</span>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-4 text-gray-400 text-sm">Tidak ada resep obat untuk kunjungan ini</div>
                    @endif
                </div>
            </div>

            <!-- Right Column - Sidebar -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Patient Info Card -->
                <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden sticky top-6">
                    <div class="bg-gradient-to-br from-blue-500 to-indigo-600 p-6 text-white text-center">
                        <div class="w-20 h-20 rounded-full flex items-center justify-center text-3xl font-bold mx-auto mb-3 shadow-lg {{ $patient->gender === 'L' ? 'bg-gradient-to-br from-blue-400 to-blue-600' : 'bg-gradient-to-br from-pink-400 to-pink-600' }}">
                            {{ strtoupper(substr($patient->name, 0, 2)) }}
                        </div>
                        <h3 class="text-lg font-bold mb-1">{{ $patient->name }}</h3>
                        <p class="text-blue-100 text-sm">{{ $patient->age }} tahun</p>
                        <div class="flex gap-2 justify-center mt-3">
                            @if($patient->gender === 'L')
                                <span class="px-3 py-1 bg-blue-500/50 backdrop-blur rounded-full text-xs font-semibold">👨 Laki-laki</span>
                            @else
                                <span class="px-3 py-1 bg-pink-500/50 backdrop-blur rounded-full text-xs font-semibold">👩 Perempuan</span>
                            @endif
                        </div>
                    </div>

                    <div class="p-4 space-y-3 text-sm">
                        <div>
                            <p class="text-gray-500 text-xs font-medium mb-0.5">No. RM</p>
                            <p class="font-mono font-semibold text-gray-900">{{ $patient->no_rm ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500 text-xs font-medium mb-0.5">NIK</p>
                            <p class="text-gray-900">{{ $patient->nik ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500 text-xs font-medium mb-0.5">Tanggal Lahir</p>
                            <p class="text-gray-900">{{ $patient->dob?->locale('id')->isoFormat('D MMMM YYYY') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Payment Summary -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-base font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <x-heroicon-o-banknotes class="w-5 h-5 text-green-600" />
                        Ringkasan Pembayaran
                    </h3>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Metode</span>
                            @if($generalVisit->payment_method === 'BPJS')
                                <span class="inline-flex items-center px-3 py-1 bg-blue-100 text-blue-800 text-xs font-bold rounded-full">BPJS</span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 bg-gray-100 text-gray-700 text-xs font-bold rounded-full">Umum</span>
                            @endif
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Status</span>
                            @if($generalVisit->status === 'Pulang')
                                <span class="inline-flex items-center px-3 py-1 bg-green-100 text-green-800 text-xs font-bold rounded-full">✓ Pulang</span>
                            @elseif($generalVisit->status === 'Rujuk')
                                <span class="inline-flex items-center px-3 py-1 bg-orange-100 text-orange-800 text-xs font-bold rounded-full">→ Rujuk</span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 bg-red-100 text-red-800 text-xs font-bold rounded-full">⛺ Rawat Inap</span>
                            @endif
                        </div>
                        <hr class="border-gray-200">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Biaya Layanan</span>
                            <span class="text-sm font-bold text-blue-700">Rp {{ number_format($generalVisit->service_fee ?? 0, 0, ',', '.') }}</span>
                        </div>
                        <div class="bg-blue-50 rounded-lg p-3 flex items-center justify-between">
                            <span class="text-sm font-bold text-gray-900">TOTAL</span>
                            <span class="text-lg font-bold text-red-600">Rp {{ number_format($generalVisit->total_price, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Quick Navigation -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-base font-bold text-gray-900 mb-3 flex items-center gap-2">
                        <x-heroicon-o-link class="w-5 h-5 text-gray-500" />
                        Navigasi Cepat
                    </h3>
                    <div class="space-y-2">
                        <a href="{{ route('patients.show', $patient) }}"
                           class="flex items-center gap-2 text-sm text-gray-600 hover:text-blue-600 hover:bg-blue-50 rounded-lg p-2 transition-colors">
                            <x-heroicon-o-user class="w-4 h-4" />
                            Profil Pasien
                        </a>
                        <a href="{{ route('general-visits.index') }}"
                           class="flex items-center gap-2 text-sm text-gray-600 hover:text-blue-600 hover:bg-blue-50 rounded-lg p-2 transition-colors">
                            <x-heroicon-o-list-bullet class="w-4 h-4" />
                            Semua Kunjungan Poli Umum
                        </a>
                        <a href="{{ route('general-visits.create', ['patient_id' => $patient->id]) }}"
                           class="flex items-center gap-2 text-sm text-gray-600 hover:text-blue-600 hover:bg-blue-50 rounded-lg p-2 transition-colors">
                            <x-heroicon-o-plus-circle class="w-4 h-4" />
                            Kunjungan Poli Umum Baru
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-dashboard-layout>
