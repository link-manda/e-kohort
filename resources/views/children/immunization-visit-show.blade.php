<x-dashboard-layout>
    <div class="mb-6">
        <!-- Breadcrumb -->
        <x-breadcrumb :items="[
            ['label' => 'Imunisasi', 'url' => route('imunisasi.index')],
            ['label' => $child->name, 'url' => route('children.show', $child)],
            ['label' => 'Detail Kunjungan']
        ]" />

        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
            <div class="flex items-center gap-4">
                <a href="{{ route('children.show', $child) }}" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                    <x-heroicon-o-arrow-left class="w-6 h-6 text-gray-600" />
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Detail Kunjungan Imunisasi</h1>
                    <p class="text-sm text-gray-500 mt-1">
                        {{ $childVisit->visit_date?->locale('id')->isoFormat('dddd, D MMMM YYYY • HH:mm') }} WIB
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-2 flex-wrap">
                <a href="{{ route('children.show', $child) }}"
                   class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-lg transition-colors text-sm">
                    <x-heroicon-o-arrow-left class="w-4 h-4" />
                    Kembali
                </a>
                <a href="{{ route('imunisasi.kunjungan', $child) }}"
                   class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg transition-colors text-sm shadow-sm">
                    <x-heroicon-o-plus class="w-4 h-4" />
                    Kunjungan Baru
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column (2/3) - Main Content -->
            <div class="lg:col-span-2 space-y-6">

                <!-- Visit Info Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                            <x-heroicon-o-calendar-days class="w-5 h-5 text-blue-600" />
                            Informasi Kunjungan
                        </h3>
                        <div class="flex items-center gap-2">
                            <!-- Payment Badge -->
                            @if($childVisit->payment_method === 'BPJS')
                                <span class="inline-flex items-center px-3 py-1 bg-blue-100 text-blue-800 text-xs font-bold rounded-full">
                                    BPJS
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 bg-gray-100 text-gray-700 text-xs font-bold rounded-full">
                                    Umum
                                </span>
                            @endif

                            @if($childVisit->informed_consent)
                                <span class="inline-flex items-center px-3 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">
                                    ✅ Consent
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4 text-sm">
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase mb-1">Tanggal Kunjungan</p>
                            <p class="text-gray-900 font-medium">{{ $childVisit->visit_date?->locale('id')->isoFormat('D MMMM YYYY') }}</p>
                            <p class="text-xs text-gray-500">{{ $childVisit->visit_date?->format('H:i') }} WIB</p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase mb-1">Usia Saat Kunjungan</p>
                            <p class="text-gray-900 font-medium">{{ $child->getAgeAtVisit($childVisit->visit_date) }}</p>
                            @if($childVisit->age_month)
                                <p class="text-xs text-gray-500">{{ $childVisit->age_month }} bulan</p>
                            @endif
                        </div>
                        @if($childVisit->complaint)
                            <div class="col-span-2 md:col-span-1">
                                <p class="text-xs font-semibold text-gray-500 uppercase mb-1">Keluhan</p>
                                <p class="text-gray-900">{{ $childVisit->complaint }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Vital Signs Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <x-heroicon-o-heart class="w-5 h-5 text-red-500" />
                        Tanda Vital
                    </h3>

                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        <!-- Temperature -->
                        <div class="rounded-lg p-4 text-center {{ $childVisit->temperature >= 38 ? 'bg-red-50 border border-red-200' : ($childVisit->temperature > 37.5 ? 'bg-yellow-50 border border-yellow-200' : 'bg-green-50 border border-green-200') }}">
                            <p class="text-xs text-gray-500 mb-1">🌡️ Suhu Tubuh</p>
                            <p class="text-2xl font-bold {{ $childVisit->temperature >= 38 ? 'text-red-600' : ($childVisit->temperature > 37.5 ? 'text-yellow-600' : 'text-green-600') }}">
                                {{ $childVisit->temperature ? number_format($childVisit->temperature, 1) : '-' }}
                            </p>
                            <p class="text-xs text-gray-500">°C</p>
                            @if($childVisit->temperature >= 38)
                                <p class="text-xs text-red-600 font-semibold mt-1">⚠️ Demam Tinggi</p>
                            @elseif($childVisit->temperature > 37.5)
                                <p class="text-xs text-yellow-600 font-semibold mt-1">⚠️ Subfebris</p>
                            @elseif($childVisit->temperature)
                                <p class="text-xs text-green-600 font-semibold mt-1">✓ Normal</p>
                            @endif
                        </div>

                        <!-- Weight -->
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 text-center">
                            <p class="text-xs text-gray-500 mb-1">⚖️ Berat Badan</p>
                            <p class="text-2xl font-bold text-blue-600">{{ $childVisit->weight ? number_format($childVisit->weight, 1) : '-' }}</p>
                            <p class="text-xs text-gray-500">kg</p>
                        </div>

                        <!-- Height -->
                        <div class="bg-indigo-50 border border-indigo-200 rounded-lg p-4 text-center">
                            <p class="text-xs text-gray-500 mb-1">📏 Tinggi Badan</p>
                            <p class="text-2xl font-bold text-indigo-600">{{ $childVisit->height ? number_format($childVisit->height, 1) : '-' }}</p>
                            <p class="text-xs text-gray-500">cm</p>
                        </div>

                        <!-- Head Circumference -->
                        @if($childVisit->head_circumference)
                            <div class="bg-purple-50 border border-purple-200 rounded-lg p-4 text-center">
                                <p class="text-xs text-gray-500 mb-1">🧠 Lingkar Kepala</p>
                                <p class="text-2xl font-bold text-purple-600">{{ number_format($childVisit->head_circumference, 1) }}</p>
                                <p class="text-xs text-gray-500">cm</p>
                            </div>
                        @endif

                        <!-- Heart Rate -->
                        @if($childVisit->heart_rate)
                            <div class="bg-pink-50 border border-pink-200 rounded-lg p-4 text-center">
                                <p class="text-xs text-gray-500 mb-1">❤️ Nadi</p>
                                <p class="text-2xl font-bold text-pink-600">{{ $childVisit->heart_rate }}</p>
                                <p class="text-xs text-gray-500">bpm</p>
                            </div>
                        @endif

                        <!-- Respiratory Rate -->
                        @if($childVisit->respiratory_rate)
                            <div class="bg-teal-50 border border-teal-200 rounded-lg p-4 text-center">
                                <p class="text-xs text-gray-500 mb-1">💨 Nafas</p>
                                <p class="text-2xl font-bold text-teal-600">{{ $childVisit->respiratory_rate }}</p>
                                <p class="text-xs text-gray-500">/menit</p>
                            </div>
                        @endif
                    </div>

                    <!-- Nutritional Status -->
                    @if($childVisit->nutritional_status)
                        @php
                            $nutriConfig = [
                                'Gizi Buruk'  => ['bg-red-50 border-red-200', 'text-red-700', '🔴'],
                                'Gizi Kurang' => ['bg-orange-50 border-orange-200', 'text-orange-700', '🟠'],
                                'Gizi Baik'   => ['bg-green-50 border-green-200', 'text-green-700', '🟢'],
                                'Gizi Lebih'  => ['bg-yellow-50 border-yellow-200', 'text-yellow-700', '🟡'],
                                'Obesitas'    => ['bg-red-50 border-red-200', 'text-red-700', '🔴'],
                            ];
                            $nc = $nutriConfig[$childVisit->nutritional_status] ?? ['bg-gray-50 border-gray-200', 'text-gray-700', '⚪'];
                        @endphp
                        <div class="mt-4 {{ $nc[0] }} border rounded-lg p-4 flex items-center gap-3">
                            <span class="text-2xl">{{ $nc[2] }}</span>
                            <div>
                                <p class="text-xs font-semibold text-gray-500 uppercase">Status Gizi</p>
                                <p class="text-lg font-bold {{ $nc[1] }}">{{ $childVisit->nutritional_status }}</p>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Immunization Actions Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                            <x-heroicon-o-shield-check class="w-5 h-5 text-green-600" />
                            Tindakan Imunisasi
                            @if($childVisit->immunizationActions->count() > 0)
                                <span class="text-sm font-normal text-gray-500">({{ $childVisit->immunizationActions->count() }} vaksin)</span>
                            @endif
                        </h3>
                    </div>

                    @if($childVisit->immunizationActions->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead class="bg-green-50 border-b border-green-200">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-green-800 uppercase">Vaksin</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-green-800 uppercase">No. Batch</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-green-800 uppercase">Lokasi Suntik</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-green-800 uppercase">Tenaga Kesehatan</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @foreach($childVisit->immunizationActions as $action)
                                        <tr class="hover:bg-green-50/50 transition-colors">
                                            <td class="px-6 py-4">
                                                <div class="flex items-center gap-2">
                                                    <span class="inline-flex items-center justify-center w-8 h-8 bg-green-100 text-green-700 rounded-full">
                                                        <x-heroicon-s-check class="w-4 h-4" />
                                                    </span>
                                                    <div>
                                                        <p class="font-semibold text-gray-900">{{ $action->vaccine_type }}</p>
                                                        @if($action->vaccine && $action->vaccine->name)
                                                            <p class="text-xs text-gray-500">{{ $action->vaccine->name }}</p>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 text-sm font-mono text-gray-700">{{ $action->batch_number ?: '-' }}</td>
                                            <td class="px-6 py-4 text-sm text-gray-700">{{ $action->body_part ?: '-' }}</td>
                                            <td class="px-6 py-4 text-sm text-gray-700 font-medium">{{ $action->provider_name ?: '-' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="p-8 text-center text-gray-500">
                            <x-heroicon-o-shield-exclamation class="w-10 h-10 mx-auto mb-2 text-gray-300" />
                            <p class="font-medium">Tidak ada tindakan vaksinasi tercatat</p>
                        </div>
                    @endif
                </div>

                <!-- Diagnosis & Medicine Card -->
                @if($childVisit->icd_code || $childVisit->diagnosis_name || $childVisit->medicine_given || $childVisit->development_notes || $childVisit->notes)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                            <x-heroicon-o-document-text class="w-5 h-5 text-purple-600" />
                            Diagnosa, Obat & Catatan
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">
                            <!-- Diagnosis -->
                            @if($childVisit->icd_code || $childVisit->diagnosis_name)
                                <div>
                                    <p class="text-xs font-semibold text-gray-500 uppercase mb-2">Diagnosa</p>
                                    <p class="text-gray-900 font-medium text-base">{{ $childVisit->diagnosis_name ?? '-' }}</p>
                                    @if($childVisit->icd_code)
                                        <p class="text-xs text-gray-500 mt-1">
                                            Kode ICD-10: <span class="font-mono font-semibold">{{ $childVisit->icd_code }}</span>
                                        </p>
                                    @endif
                                </div>
                            @endif

                            <!-- Medicine -->
                            @if($childVisit->medicine_given)
                                <div>
                                    <p class="text-xs font-semibold text-gray-500 uppercase mb-2">💊 Obat Diberikan</p>
                                    <div class="bg-green-50 border border-green-200 rounded-lg p-3">
                                        <p class="font-semibold text-green-900">{{ $childVisit->medicine_given }}</p>
                                        @if($childVisit->medicine_dosage)
                                            <p class="text-xs text-green-700 mt-1">Dosis: {{ $childVisit->medicine_dosage }}</p>
                                        @endif
                                    </div>
                                </div>
                            @endif

                            <!-- Development Notes -->
                            @if($childVisit->development_notes)
                                <div class="md:col-span-2">
                                    <p class="text-xs font-semibold text-gray-500 uppercase mb-2">📝 Catatan Perkembangan</p>
                                    <p class="text-gray-700 bg-gray-50 rounded-lg p-3 border border-gray-200">{{ $childVisit->development_notes }}</p>
                                </div>
                            @endif

                            <!-- Notes -->
                            @if($childVisit->notes)
                                <div class="md:col-span-2">
                                    <p class="text-xs font-semibold text-gray-500 uppercase mb-2">📋 Keterangan Tambahan</p>
                                    <p class="text-gray-700 bg-gray-50 rounded-lg p-3 border border-gray-200">{{ $childVisit->notes }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>

            <!-- Right Column (1/3) - Sidebar -->
            <div class="lg:col-span-1 space-y-6">

                <!-- Child Info Card -->
                <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden sticky top-6">
                    <div class="bg-gradient-to-br from-green-500 to-teal-600 p-6 text-white text-center">
                        <div class="w-20 h-20 rounded-full flex items-center justify-center text-3xl font-bold mx-auto mb-3 shadow-lg {{ $child->gender === 'L' ? 'bg-gradient-to-br from-blue-400 to-blue-600' : 'bg-gradient-to-br from-pink-400 to-pink-600' }}">
                            {{ strtoupper(substr($child->name, 0, 2)) }}
                        </div>
                        <h3 class="text-lg font-bold mb-1">{{ $child->name }}</h3>
                        <p class="text-green-100 text-sm">{{ $child->getDetailedAge() }}</p>
                        <div class="flex gap-2 justify-center mt-3">
                            @if($child->gender === 'L')
                                <span class="px-3 py-1 bg-blue-500/50 backdrop-blur rounded-full text-xs font-semibold">👦 Laki-laki</span>
                            @else
                                <span class="px-3 py-1 bg-pink-500/50 backdrop-blur rounded-full text-xs font-semibold">👧 Perempuan</span>
                            @endif
                        </div>
                    </div>

                    <div class="p-4 space-y-3 text-sm">
                        <div>
                            <p class="text-gray-500 text-xs font-medium mb-0.5">No. RM</p>
                            <p class="font-mono font-semibold text-gray-900">{{ $child->no_rm ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500 text-xs font-medium mb-0.5">Tanggal Lahir</p>
                            <p class="text-gray-900">{{ $child->dob?->locale('id')->isoFormat('D MMMM YYYY') }}</p>
                        </div>
                        @if($child->patient)
                            <div>
                                <p class="text-gray-500 text-xs font-medium mb-0.5">Nama Ibu</p>
                                <p class="text-gray-900 font-medium">{{ $child->parent_display_name }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Payment Summary Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-base font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <x-heroicon-o-banknotes class="w-5 h-5 text-green-600" />
                        Ringkasan Pembayaran
                    </h3>

                    <div class="space-y-3">
                        <!-- Payment Method -->
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Metode</span>
                            @if($childVisit->payment_method === 'BPJS')
                                <span class="inline-flex items-center px-3 py-1 bg-blue-100 text-blue-800 text-xs font-bold rounded-full">
                                    BPJS
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 bg-gray-100 text-gray-700 text-xs font-bold rounded-full">
                                    Umum
                                </span>
                            @endif
                        </div>

                        <hr class="border-gray-200">

                        <!-- Service Fee -->
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Biaya Layanan</span>
                            <span class="text-sm font-bold text-blue-700">
                                Rp {{ number_format($childVisit->service_fee ?? 0, 0, ',', '.') }}
                            </span>
                        </div>

                        <hr class="border-gray-200">

                        <!-- Total -->
                        <div class="bg-blue-50 rounded-lg p-3 flex items-center justify-between">
                            <span class="text-sm font-bold text-gray-900">TOTAL</span>
                            <span class="text-lg font-bold text-blue-700">
                                Rp {{ number_format($childVisit->service_fee ?? 0, 0, ',', '.') }}
                            </span>
                        </div>

                        <!-- Informed Consent -->
                        <div class="flex items-center gap-2 text-xs mt-2 {{ $childVisit->informed_consent ? 'text-green-600' : 'text-red-600' }}">
                            @if($childVisit->informed_consent)
                                <x-heroicon-s-check-circle class="w-4 h-4" />
                                <span>Informed consent diberikan</span>
                            @else
                                <x-heroicon-s-x-circle class="w-4 h-4" />
                                <span>Informed consent tidak tercatat</span>
                            @endif
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
                        <a href="{{ route('children.show', $child) }}"
                           class="flex items-center gap-2 text-sm text-gray-600 hover:text-blue-600 hover:bg-blue-50 rounded-lg p-2 transition-colors">
                            <x-heroicon-o-user class="w-4 h-4" />
                            Profil Anak
                        </a>
                        <a href="{{ route('imunisasi.kunjungan', $child) }}"
                           class="flex items-center gap-2 text-sm text-gray-600 hover:text-green-600 hover:bg-green-50 rounded-lg p-2 transition-colors">
                            <x-heroicon-o-plus-circle class="w-4 h-4" />
                            Catat Kunjungan Baru
                        </a>
                        <a href="{{ route('imunisasi.index') }}"
                           class="flex items-center gap-2 text-sm text-gray-600 hover:text-purple-600 hover:bg-purple-50 rounded-lg p-2 transition-colors">
                            <x-heroicon-o-list-bullet class="w-4 h-4" />
                            Daftar Semua Anak
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-dashboard-layout>
