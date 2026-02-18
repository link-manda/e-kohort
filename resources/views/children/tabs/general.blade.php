@php
    $generalVisits = $child->generalVisits ?? collect();
@endphp

<div class="space-y-6">
    <!-- General Visits List -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-6 border-b border-gray-200 flex items-center justify-between">
            <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                <x-heroicon-o-clipboard-document-check class="w-5 h-5 text-blue-600" />
                Riwayat Kunjungan Poli Umum
            </h3>
            <a href="{{ route('children.general-visit', $child) }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-colors text-sm">
                <x-heroicon-o-plus class="w-4 h-4" />
                Tambah Kunjungan
            </a>
        </div>

        @if($generalVisits->count() > 0)
            <div class="divide-y divide-gray-200">
                @foreach($generalVisits->sortByDesc('visit_date') as $visit)
                    <div class="p-4 hover:bg-gray-50/50 transition-colors" x-data="{ expanded: false }">
                        <!-- Collapsed Card Header (always visible) -->
                        <div class="flex items-start justify-between gap-4 cursor-pointer" @click="expanded = !expanded">
                            <div class="flex-1 min-w-0">
                                <!-- Date + Time -->
                                <div class="flex items-center gap-2 mb-1 flex-wrap">
                                    <p class="font-semibold text-gray-900">
                                        {{ $visit->visit_date?->locale('id')->isoFormat('dddd, D MMMM YYYY') }}
                                    </p>
                                    <span class="text-xs text-gray-500">
                                        {{ $visit->visit_date?->format('H:i') }} WIB
                                    </span>
                                </div>

                                <!-- Badges Row -->
                                <div class="flex items-center gap-2 flex-wrap mb-2">
                                    <!-- Emergency Badge -->
                                    @if($visit->is_emergency)
                                        <span class="inline-flex items-center px-2 py-0.5 bg-red-100 text-red-800 text-xs font-semibold rounded-full">
                                            🚨 Darurat
                                        </span>
                                    @endif

                                    <!-- Status Badge -->
                                    @if($visit->status === 'completed')
                                        <span class="inline-flex items-center px-2 py-0.5 bg-green-100 text-green-800 text-xs font-semibold rounded-full">
                                            ✓ Selesai
                                        </span>
                                    @elseif($visit->status === 'Rujuk')
                                        <span class="inline-flex items-center px-2 py-0.5 bg-orange-100 text-orange-800 text-xs font-semibold rounded-full">
                                            → Rujuk
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 bg-yellow-100 text-yellow-800 text-xs font-semibold rounded-full">
                                            ● Aktif
                                        </span>
                                    @endif

                                    <!-- Payment Badge -->
                                    @if($visit->payment_method === 'BPJS')
                                        <span class="inline-flex items-center px-2 py-0.5 bg-blue-100 text-blue-800 text-xs font-semibold rounded-full">
                                            BPJS
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 bg-gray-100 text-gray-700 text-xs font-semibold rounded-full">
                                            Umum
                                        </span>
                                    @endif
                                </div>

                                <!-- Complaint (truncated) -->
                                @if($visit->complaint)
                                    <div class="mb-2">
                                        <span class="text-xs text-gray-500 font-medium">Keluhan:</span>
                                        <p class="text-sm text-gray-700">{{ Str::limit($visit->complaint, 120) }}</p>
                                    </div>
                                @endif

                                <!-- Diagnosis -->
                                @if($visit->diagnosis)
                                    <div class="mb-2">
                                        <span class="text-xs text-gray-500 font-medium">Diagnosa:</span>
                                        <p class="text-sm text-gray-900 font-medium">
                                            {{ $visit->diagnosis }}
                                            @if($visit->icd10_code)
                                                <span class="text-gray-500 font-normal">({{ $visit->icd10_code }})</span>
                                            @endif
                                        </p>
                                    </div>
                                @endif

                                <!-- Quick Info Tags -->
                                <div class="flex flex-wrap gap-2 mt-2">
                                    @if($visit->temperature)
                                        <span class="inline-flex items-center gap-1 text-xs {{ $visit->temperature >= 38 ? 'text-red-600 bg-red-50' : ($visit->temperature > 37.5 ? 'text-yellow-600 bg-yellow-50' : 'text-gray-600 bg-gray-100') }} px-2 py-1 rounded">
                                            🌡️ {{ number_format($visit->temperature, 1) }}°C
                                        </span>
                                    @endif
                                    @if($visit->weight)
                                        <span class="inline-flex items-center gap-1 text-xs text-gray-600 bg-gray-100 px-2 py-1 rounded">
                                            ⚖️ {{ number_format($visit->weight, 1) }} kg
                                        </span>
                                    @endif
                                    @if($visit->systolic && $visit->diastolic)
                                        <span class="inline-flex items-center gap-1 text-xs text-gray-600 bg-gray-100 px-2 py-1 rounded">
                                            🩺 {{ $visit->systolic }}/{{ $visit->diastolic }} mmHg
                                        </span>
                                    @endif
                                    @if($visit->prescriptions && $visit->prescriptions->count() > 0)
                                        <span class="inline-flex items-center gap-1 text-xs text-green-600 bg-green-50 px-2 py-1 rounded">
                                            💊 {{ $visit->prescriptions->count() }} obat
                                        </span>
                                    @endif
                                    @if($visit->service_fee)
                                        <span class="inline-flex items-center gap-1 text-xs text-blue-600 bg-blue-50 px-2 py-1 rounded font-medium">
                                            💰 Rp {{ number_format($visit->service_fee, 0, ',', '.') }}
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- Right: Expand Toggle + Detail Link -->
                            <div class="flex flex-col items-end gap-2 flex-shrink-0">
                                <a href="{{ route('children.general-visit.show', [$child, $visit]) }}"
                                   class="text-blue-600 hover:text-blue-800 text-sm font-medium"
                                   @click.stop>
                                    Detail →
                                </a>
                                <button class="text-gray-400 hover:text-gray-600 transition-transform"
                                        :class="{ 'rotate-180': expanded }">
                                    <x-heroicon-o-chevron-down class="w-5 h-5" />
                                </button>
                            </div>
                        </div>

                        <!-- Expanded Detail -->
                        <div x-show="expanded" x-collapse x-cloak class="mt-4 border-t border-gray-100 pt-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">

                                <!-- Vital Signs -->
                                <div>
                                    <p class="text-xs font-semibold text-gray-500 uppercase mb-2">Tanda Vital (Objective)</p>
                                    <div class="space-y-1.5 text-xs">
                                        @if($visit->systolic && $visit->diastolic)
                                            <p>
                                                <span class="text-gray-500 w-28 inline-block">Tensi:</span>
                                                <span class="font-medium text-gray-900">{{ $visit->systolic }}/{{ $visit->diastolic }} mmHg</span>
                                            </p>
                                        @endif
                                        @if($visit->temperature)
                                            <p>
                                                <span class="text-gray-500 w-28 inline-block">Suhu:</span>
                                                <span class="font-medium {{ $visit->temperature >= 38 ? 'text-red-600' : 'text-gray-900' }}">
                                                    {{ number_format($visit->temperature, 1) }}°C
                                                    @if($visit->temperature >= 38) ⚠️ Demam @endif
                                                </span>
                                            </p>
                                        @endif
                                        @if($visit->weight)
                                            <p><span class="text-gray-500 w-28 inline-block">Berat Badan:</span> <span class="font-medium text-gray-900">{{ number_format($visit->weight, 1) }} kg</span></p>
                                        @endif
                                        @if($visit->height)
                                            <p><span class="text-gray-500 w-28 inline-block">Tinggi Badan:</span> <span class="font-medium text-gray-900">{{ number_format($visit->height, 1) }} cm</span></p>
                                        @endif
                                        @if($visit->heart_rate)
                                            <p><span class="text-gray-500 w-28 inline-block">Nadi:</span> <span class="font-medium text-gray-900">{{ $visit->heart_rate }} bpm</span></p>
                                        @endif
                                        @if($visit->respiratory_rate)
                                            <p><span class="text-gray-500 w-28 inline-block">Nafas:</span> <span class="font-medium text-gray-900">{{ $visit->respiratory_rate }} /menit</span></p>
                                        @endif
                                        @if($visit->waist_circumference)
                                            <p><span class="text-gray-500 w-28 inline-block">Lingkar Perut:</span> <span class="font-medium text-gray-900">{{ number_format($visit->waist_circumference, 1) }} cm</span></p>
                                        @endif
                                        @if($visit->bmi)
                                            <p><span class="text-gray-500 w-28 inline-block">BMI:</span> <span class="font-medium text-gray-900">{{ number_format($visit->bmi, 1) }}</span></p>
                                        @endif
                                    </div>
                                </div>

                                <!-- Assessment & Plan -->
                                <div>
                                    @if($visit->diagnosis)
                                        <p class="text-xs font-semibold text-gray-500 uppercase mb-2">Diagnosa (Assessment)</p>
                                        <p class="text-sm text-gray-900 font-medium mb-1">
                                            {{ $visit->diagnosis }}
                                            @if($visit->icd10_code)
                                                <span class="text-gray-500 font-normal">({{ $visit->icd10_code }})</span>
                                            @endif
                                        </p>
                                    @endif

                                    @if($visit->therapy)
                                        <p class="text-xs font-semibold text-gray-500 uppercase mb-2 mt-3">Terapi/Tindakan (Plan)</p>
                                        <p class="text-sm text-gray-700">{{ $visit->therapy }}</p>
                                    @endif

                                    @if($visit->consciousness)
                                        <p class="text-xs font-semibold text-gray-500 uppercase mb-2 mt-3">Kesadaran</p>
                                        <p class="text-sm text-gray-700">{{ $visit->consciousness }}</p>
                                    @endif

                                    @if($visit->allergies)
                                        <p class="text-xs font-semibold text-gray-500 uppercase mb-2 mt-3">Alergi</p>
                                        <p class="text-sm text-red-600">⚠️ {{ $visit->allergies }}</p>
                                    @endif
                                </div>

                                <!-- Prescriptions Table -->
                                @if($visit->prescriptions && $visit->prescriptions->count() > 0)
                                    <div class="col-span-2">
                                        <p class="text-xs font-semibold text-gray-500 uppercase mb-2">💊 Resep Obat ({{ $visit->prescriptions->count() }} item)</p>
                                        <div class="bg-green-50 border border-green-200 rounded-lg overflow-hidden">
                                            <table class="w-full text-xs">
                                                <thead class="bg-green-100">
                                                    <tr>
                                                        <th class="px-3 py-2 text-left text-gray-700 font-semibold">Nama Obat</th>
                                                        <th class="px-3 py-2 text-left text-gray-700 font-semibold">Jumlah</th>
                                                        <th class="px-3 py-2 text-left text-gray-700 font-semibold">Dosis/Signa</th>
                                                        <th class="px-3 py-2 text-right text-gray-700 font-semibold">Harga</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="divide-y divide-green-200">
                                                    @foreach($visit->prescriptions as $rx)
                                                        <tr>
                                                            <td class="px-3 py-2 font-medium text-green-900">{{ $rx->medicine_name }}</td>
                                                            <td class="px-3 py-2 text-gray-700">{{ $rx->quantity }}</td>
                                                            <td class="px-3 py-2 text-gray-700">
                                                                {{ $rx->dosage ?? '' }}
                                                                @if($rx->signa) • {{ $rx->signa }} @endif
                                                                @if($rx->frequency) • {{ $rx->frequency }} @endif
                                                            </td>
                                                            <td class="px-3 py-2 text-right font-bold text-green-700">Rp {{ number_format($rx->total_price, 0, ',', '.') }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                            <div class="px-3 py-2 bg-green-100 flex justify-between items-center border-t border-green-300">
                                                <span class="text-xs font-semibold text-gray-700">Total Biaya Obat:</span>
                                                <span class="text-sm font-bold text-green-700">Rp {{ number_format($visit->prescriptions->sum('total_price'), 0, ',', '.') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <!-- Physical Exam -->
                                @if($visit->physical_exam)
                                    <div class="col-span-2">
                                        <p class="text-xs font-semibold text-gray-500 uppercase mb-1">Pemeriksaan Fisik</p>
                                        <p class="text-sm text-gray-700 bg-gray-50 rounded-lg p-3 border border-gray-200">{{ $visit->physical_exam }}</p>
                                    </div>
                                @endif

                                <!-- Service Fee -->
                                <div class="col-span-2">
                                    <div class="bg-blue-50 border border-blue-200 rounded-md p-3 space-y-2">
                                        <div class="flex justify-between items-center text-xs">
                                            <p class="font-semibold text-gray-700">💵 Biaya Jasa Layanan:</p>
                                            <p class="font-bold text-blue-700">Rp {{ number_format($visit->service_fee ?? 0, 0, ',', '.') }}</p>
                                        </div>
                                        @if($visit->prescriptions && $visit->prescriptions->count() > 0)
                                            <div class="flex justify-between items-center text-xs">
                                                <p class="text-gray-500">💊 Total Obat:</p>
                                                <p class="font-bold text-green-700">Rp {{ number_format($visit->prescriptions->sum('total_price'), 0, ',', '.') }}</p>
                                            </div>
                                        @endif
                                        <div class="pt-2 border-t-2 border-blue-300 flex justify-between items-center">
                                            <p class="text-sm font-bold text-gray-900">TOTAL BIAYA:</p>
                                            <p class="text-lg font-bold text-red-600">Rp {{ number_format($visit->total_price, 0, ',', '.') }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="p-8 text-center text-gray-500">
                <x-heroicon-o-document-text class="w-12 h-12 mx-auto mb-3 text-gray-300" />
                <p class="font-medium">Belum ada riwayat kunjungan poli umum</p>
                <p class="text-sm mt-1">Riwayat akan muncul setelah anak berkunjung ke poli umum</p>
                <a href="{{ route('children.general-visit', $child) }}"
                   class="inline-flex items-center gap-2 mt-4 text-sm text-blue-600 hover:text-blue-800 font-medium">
                    <x-heroicon-o-plus class="w-4 h-4" />
                    Daftarkan Kunjungan Baru
                </a>
            </div>
        @endif
    </div>
</div>
