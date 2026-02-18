@php
    // Get immunization data from child visits
    $immunizationVisits = $child->childVisits ?? collect();
    $immunizationActions = $immunizationVisits->flatMap->immunizationActions ?? collect();
    $completedVaccines = $immunizationActions->pluck('vaccine_type')->unique();

    // Required vaccines list
    $requiredVaccines = [
        'HB 0' => 'Hepatitis B (Lahir)',
        'BCG' => 'BCG',
        'Polio 1' => 'Polio 1',
        'DPT-HB-Hib 1' => 'DPT-HB-Hib 1',
        'Polio 2' => 'Polio 2',
        'DPT-HB-Hib 2' => 'DPT-HB-Hib 2',
        'Polio 3' => 'Polio 3',
        'DPT-HB-Hib 3' => 'DPT-HB-Hib 3',
        'Polio 4' => 'Polio 4',
        'IPV' => 'IPV',
        'Campak-Rubella' => 'Campak Rubella',
    ];

    $totalRequired = count($requiredVaccines);
    $totalCompleted = $completedVaccines->count();
    $progressPercent = $totalRequired > 0 ? round(($totalCompleted / $totalRequired) * 100) : 0;
@endphp

<div class="space-y-6">
    <!-- Immunization Progress -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
            <x-heroicon-o-shield-check class="w-5 h-5 text-green-600" />
            Progress Imunisasi Dasar
        </h3>

        <div class="mb-4">
            <div class="flex justify-between items-center mb-2">
                <span class="text-sm text-gray-600">{{ $totalCompleted }} dari {{ $totalRequired }} vaksin lengkap</span>
                <span class="text-sm font-bold {{ $progressPercent >= 100 ? 'text-green-600' : 'text-gray-700' }}">{{ $progressPercent }}%</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-3">
                <div class="h-3 rounded-full transition-all {{ $progressPercent >= 100 ? 'bg-green-500' : 'bg-blue-500' }}"
                     style="width: {{ $progressPercent }}%"></div>
            </div>
        </div>

        <!-- Vaccines Grid -->
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
            @foreach($requiredVaccines as $code => $name)
                @php
                    $isCompleted = $completedVaccines->contains($code);
                @endphp
                <div class="flex items-center gap-2 p-3 rounded-lg {{ $isCompleted ? 'bg-green-50 border border-green-200' : 'bg-gray-50 border border-gray-200' }}">
                    @if($isCompleted)
                        <x-heroicon-s-check-circle class="w-5 h-5 text-green-600 flex-shrink-0" />
                    @else
                        <x-heroicon-o-clock class="w-5 h-5 text-gray-400 flex-shrink-0" />
                    @endif
                    <span class="text-sm {{ $isCompleted ? 'text-green-800 font-medium' : 'text-gray-600' }}">{{ $name }}</span>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Immunization History -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-6 border-b border-gray-200 flex items-center justify-between">
            <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                <x-heroicon-o-calendar-days class="w-5 h-5 text-blue-600" />
                Riwayat Kunjungan Imunisasi
            </h3>
            <a href="{{ route('imunisasi.kunjungan', $child) }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg transition-colors text-sm">
                <x-heroicon-o-plus class="w-4 h-4" />
                Tambah Kunjungan
            </a>
        </div>

        @if($immunizationVisits->count() > 0)
            <div class="divide-y divide-gray-200">
                @foreach($immunizationVisits->sortByDesc('visit_date') as $visit)
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

                                <!-- Age + Badges Row -->
                                <div class="flex items-center gap-2 flex-wrap mb-2">
                                    <span class="text-sm text-gray-500">
                                        Usia: {{ $child->getAgeAtVisit($visit->visit_date) }}
                                    </span>

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

                                    <!-- Nutritional Status Badge -->
                                    @if($visit->nutritional_status)
                                        @php
                                            $nutriColors = [
                                                'Gizi Buruk' => 'bg-red-100 text-red-800',
                                                'Gizi Kurang' => 'bg-orange-100 text-orange-800',
                                                'Gizi Baik' => 'bg-green-100 text-green-800',
                                                'Gizi Lebih' => 'bg-yellow-100 text-yellow-800',
                                                'Obesitas' => 'bg-red-100 text-red-800',
                                            ];
                                            $nutriClass = $nutriColors[$visit->nutritional_status] ?? 'bg-gray-100 text-gray-700';
                                        @endphp
                                        <span class="inline-flex items-center px-2 py-0.5 text-xs font-semibold rounded-full {{ $nutriClass }}">
                                            {{ $visit->nutritional_status }}
                                        </span>
                                    @endif
                                </div>

                                <!-- Vaccine Badges -->
                                @if($visit->immunizationActions->count() > 0)
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($visit->immunizationActions as $action)
                                            <span class="inline-flex items-center px-2.5 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">
                                                <x-heroicon-s-check class="w-3 h-3 mr-1" />
                                                {{ $action->vaccine_type }}
                                            </span>
                                        @endforeach
                                    </div>
                                @endif

                                <!-- Quick Vital Signs Summary (collapsed) -->
                                <div class="flex flex-wrap gap-3 mt-2">
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
                                    @if($visit->height)
                                        <span class="inline-flex items-center gap-1 text-xs text-gray-600 bg-gray-100 px-2 py-1 rounded">
                                            📏 {{ number_format($visit->height, 1) }} cm
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
                                <a href="{{ route('children.visit.show', [$child, $visit]) }}"
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

                        <!-- Expanded Detail (toggle) -->
                        <div x-show="expanded" x-collapse x-cloak class="mt-4 border-t border-gray-100 pt-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">

                                <!-- Vital Signs -->
                                <div>
                                    <p class="text-xs font-semibold text-gray-500 uppercase mb-2">Tanda Vital</p>
                                    <div class="space-y-1.5 text-xs">
                                        @if($visit->temperature)
                                            <p>
                                                <span class="text-gray-500 w-24 inline-block">Suhu:</span>
                                                <span class="font-medium {{ $visit->temperature >= 38 ? 'text-red-600' : 'text-gray-900' }}">
                                                    {{ number_format($visit->temperature, 1) }}°C
                                                    @if($visit->temperature >= 38) ⚠️ Demam @endif
                                                </span>
                                            </p>
                                        @endif
                                        @if($visit->weight)
                                            <p><span class="text-gray-500 w-24 inline-block">Berat Badan:</span> <span class="font-medium text-gray-900">{{ number_format($visit->weight, 1) }} kg</span></p>
                                        @endif
                                        @if($visit->height)
                                            <p><span class="text-gray-500 w-24 inline-block">Tinggi Badan:</span> <span class="font-medium text-gray-900">{{ number_format($visit->height, 1) }} cm</span></p>
                                        @endif
                                        @if($visit->heart_rate)
                                            <p><span class="text-gray-500 w-24 inline-block">Nadi:</span> <span class="font-medium text-gray-900">{{ $visit->heart_rate }} bpm</span></p>
                                        @endif
                                        @if($visit->respiratory_rate)
                                            <p><span class="text-gray-500 w-24 inline-block">Nafas:</span> <span class="font-medium text-gray-900">{{ $visit->respiratory_rate }} /menit</span></p>
                                        @endif
                                        @if($visit->head_circumference)
                                            <p><span class="text-gray-500 w-24 inline-block">Lingkar Kepala:</span> <span class="font-medium text-gray-900">{{ number_format($visit->head_circumference, 1) }} cm</span></p>
                                        @endif
                                    </div>
                                </div>

                                <!-- Diagnosis & Medicine -->
                                <div>
                                    @if($visit->icd_code || $visit->diagnosis_name)
                                        <p class="text-xs font-semibold text-gray-500 uppercase mb-2">Diagnosa</p>
                                        <p class="text-sm text-gray-900 font-medium mb-1">
                                            {{ $visit->diagnosis_name ?? '-' }}
                                            @if($visit->icd_code)
                                                <span class="text-gray-500">({{ $visit->icd_code }})</span>
                                            @endif
                                        </p>
                                    @endif

                                    @if($visit->medicine_given)
                                        <p class="text-xs font-semibold text-gray-500 uppercase mb-2 mt-3">Obat Diberikan</p>
                                        <p class="text-sm text-gray-900">
                                            💊 {{ $visit->medicine_given }}
                                            @if($visit->medicine_dosage)
                                                <span class="text-gray-500">• {{ $visit->medicine_dosage }}</span>
                                            @endif
                                        </p>
                                    @endif

                                    @if($visit->complaint)
                                        <p class="text-xs font-semibold text-gray-500 uppercase mb-2 mt-3">Keluhan</p>
                                        <p class="text-sm text-gray-700">{{ $visit->complaint }}</p>
                                    @endif
                                </div>

                                <!-- Vaccine Details Table -->
                                @if($visit->immunizationActions->count() > 0)
                                    <div class="col-span-2">
                                        <p class="text-xs font-semibold text-gray-500 uppercase mb-2">Detail Vaksinasi</p>
                                        <div class="bg-green-50 border border-green-200 rounded-lg overflow-hidden">
                                            <table class="w-full text-xs">
                                                <thead class="bg-green-100">
                                                    <tr>
                                                        <th class="px-3 py-2 text-left text-gray-700 font-semibold">Vaksin</th>
                                                        <th class="px-3 py-2 text-left text-gray-700 font-semibold">No. Batch</th>
                                                        <th class="px-3 py-2 text-left text-gray-700 font-semibold">Lokasi</th>
                                                        <th class="px-3 py-2 text-left text-gray-700 font-semibold">Nakes</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="divide-y divide-green-200">
                                                    @foreach($visit->immunizationActions as $action)
                                                        <tr>
                                                            <td class="px-3 py-2 font-medium text-green-900">{{ $action->vaccine_type }}</td>
                                                            <td class="px-3 py-2 text-gray-700 font-mono">{{ $action->batch_number ?: '-' }}</td>
                                                            <td class="px-3 py-2 text-gray-700">{{ $action->body_part ?: '-' }}</td>
                                                            <td class="px-3 py-2 text-gray-700">{{ $action->provider_name ?: '-' }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                @endif

                                <!-- Notes & Development -->
                                @if($visit->development_notes || $visit->notes)
                                    <div class="col-span-2">
                                        @if($visit->development_notes)
                                            <p class="text-xs font-semibold text-gray-500 uppercase mb-1">Catatan Perkembangan</p>
                                            <p class="text-sm text-gray-700 mb-2">{{ $visit->development_notes }}</p>
                                        @endif
                                        @if($visit->notes)
                                            <p class="text-xs font-semibold text-gray-500 uppercase mb-1">Keterangan</p>
                                            <p class="text-sm text-gray-700">{{ $visit->notes }}</p>
                                        @endif
                                    </div>
                                @endif

                                <!-- Service Fee -->
                                <div class="col-span-2">
                                    <div class="bg-blue-50 border border-blue-200 rounded-md p-3">
                                        <div class="flex justify-between items-center text-xs">
                                            <p class="font-semibold text-gray-700">💵 Biaya Jasa Layanan:</p>
                                            <p class="font-bold text-blue-700 text-sm">
                                                Rp {{ number_format($visit->service_fee ?? 0, 0, ',', '.') }}
                                            </p>
                                        </div>
                                        <div class="flex justify-between items-center text-xs mt-1">
                                            <p class="text-gray-500">Pembayaran:</p>
                                            <p class="font-medium text-gray-700">{{ $visit->payment_method ?? 'Umum' }}</p>
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
                <x-heroicon-o-shield-exclamation class="w-12 h-12 mx-auto mb-3 text-gray-300" />
                <p class="font-medium">Belum ada riwayat imunisasi</p>
                <p class="text-sm mt-1">Klik tombol "Tambah Kunjungan" untuk mencatat imunisasi pertama</p>
                <a href="{{ route('imunisasi.kunjungan', $child) }}"
                   class="inline-flex items-center gap-2 mt-4 text-sm text-green-600 hover:text-green-800 font-medium">
                    <x-heroicon-o-plus class="w-4 h-4" />
                    Daftarkan Kunjungan Baru
                </a>
            </div>
        @endif
    </div>
</div>
