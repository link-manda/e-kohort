<!-- Riwayat Persalinan & Nifas -->
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
            <x-heroicon-o-sparkles class="w-6 h-6 text-purple-600" />
            Riwayat Persalinan & Perawatan Nifas
        </h3>
    </div>

    @php
        $deliveredPregnancies = $patient->pregnancies->where('status', 'Lahir')->sortByDesc('delivery_date');
    @endphp

    @if ($deliveredPregnancies->count() > 0)
        @foreach ($deliveredPregnancies as $pregnancy)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <!-- Delivery Header -->
                <div class="bg-gradient-to-r from-purple-500 to-purple-600 p-4 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <h4 class="font-bold text-lg">Persalinan ke-{{ $pregnancy->gravida }}</h4>
                            @if ($pregnancy->delivery_date)
                                <p class="text-sm opacity-90">
                                    {{ $pregnancy->delivery_date->locale('id')->isoFormat('dddd, D MMMM YYYY') }}
                                </p>
                            @elseif($pregnancy->deliveryRecord && $pregnancy->deliveryRecord->delivery_date_time)
                                <p class="text-sm opacity-90">
                                    {{ $pregnancy->deliveryRecord->delivery_date_time->locale('id')->isoFormat('dddd, D MMMM YYYY') }}
                                </p>
                            @endif
                        </div>
                        <span class="px-3 py-1 bg-white/20 backdrop-blur rounded-full text-sm font-semibold">
                            G{{ $pregnancy->gravida }}P{{ $pregnancy->para }}A{{ $pregnancy->abortus }}
                        </span>
                    </div>
                </div>

                <!-- Delivery Info -->
                <div class="p-4">
                    @if ($pregnancy->deliveryRecord)
                        @php $delivery = $pregnancy->deliveryRecord; @endphp

                        <!-- Delivery Stats Grid -->
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-4">
                            <div class="text-center p-3 bg-blue-50 rounded-lg">
                                <p class="text-xs text-blue-600 font-medium mb-1">Cara Persalinan</p>
                                <p class="text-sm font-bold text-blue-900">{{ $delivery->delivery_method }}</p>
                            </div>
                            <div class="text-center p-3 bg-green-50 rounded-lg">
                                <p class="text-xs text-green-600 font-medium mb-1">Penolong</p>
                                <p class="text-sm font-bold text-green-900">{{ $delivery->birth_attendant }}</p>
                            </div>
                            <div class="text-center p-3 bg-orange-50 rounded-lg">
                                <p class="text-xs text-orange-600 font-medium mb-1">Tempat</p>
                                <p class="text-sm font-bold text-orange-900">{{ $delivery->place_of_birth ?? '-' }}</p>
                            </div>
                            <div class="text-center p-3 bg-purple-50 rounded-lg">
                                <p class="text-xs text-purple-600 font-medium mb-1">UK Saat Lahir</p>
                                <p class="text-sm font-bold text-purple-900">{{ $delivery->gestational_age ?? '-' }} minggu</p>
                            </div>
                        </div>

                        <!-- Baby Bio Section -->
                        <div class="bg-gradient-to-r from-pink-50 to-blue-50 rounded-xl p-4 mb-4 border border-pink-100">
                            <h5 class="text-sm font-bold text-gray-800 mb-3 flex items-center gap-2">
                                <span class="text-lg">👶</span> Data Bayi
                            </h5>

                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
                                <!-- Baby Name -->
                                <div class="col-span-2">
                                    <p class="text-xs text-gray-500 mb-1">Nama Bayi</p>
                                    <p class="font-semibold text-gray-900">{{ $delivery->baby_name ?? 'Belum diberi nama' }}</p>
                                </div>

                                <!-- Gender Badge -->
                                <div>
                                    <p class="text-xs text-gray-500 mb-1">Jenis Kelamin</p>
                                    @if($delivery->gender === 'L')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                                            👦 Laki-laki
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-pink-100 text-pink-800">
                                            👧 Perempuan
                                        </span>
                                    @endif
                                </div>

                                <!-- Condition Badge -->
                                <div>
                                    <p class="text-xs text-gray-500 mb-1">Kondisi</p>
                                    @if($delivery->condition === 'Hidup')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                            ✅ Hidup
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                                            ⚠️ {{ $delivery->condition }}
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- Anthropometry -->
                            <div class="grid grid-cols-3 md:grid-cols-5 gap-3 mb-4">
                                <div class="text-center p-2 bg-white rounded-lg shadow-sm">
                                    <p class="text-xs text-gray-500">Berat Lahir</p>
                                    <p class="text-lg font-bold text-orange-600">{{ number_format($delivery->birth_weight ?? 0, 0) }}</p>
                                    <p class="text-xs text-gray-400">gram</p>
                                </div>
                                <div class="text-center p-2 bg-white rounded-lg shadow-sm">
                                    <p class="text-xs text-gray-500">Panjang</p>
                                    <p class="text-lg font-bold text-blue-600">{{ number_format($delivery->birth_length ?? 0, 1) }}</p>
                                    <p class="text-xs text-gray-400">cm</p>
                                </div>
                                <div class="text-center p-2 bg-white rounded-lg shadow-sm">
                                    <p class="text-xs text-gray-500">Lingkar Kepala</p>
                                    <p class="text-lg font-bold text-purple-600">{{ number_format($delivery->head_circumference ?? 0, 1) }}</p>
                                    <p class="text-xs text-gray-400">cm</p>
                                </div>
                                <div class="text-center p-2 bg-white rounded-lg shadow-sm">
                                    <p class="text-xs text-gray-500">APGAR 1'</p>
                                    <p class="text-lg font-bold {{ ($delivery->apgar_score_1 ?? 0) >= 7 ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $delivery->apgar_score_1 ?? '-' }}
                                    </p>
                                </div>
                                <div class="text-center p-2 bg-white rounded-lg shadow-sm">
                                    <p class="text-xs text-gray-500">APGAR 5'</p>
                                    <p class="text-lg font-bold {{ ($delivery->apgar_score_5 ?? 0) >= 7 ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $delivery->apgar_score_5 ?? '-' }}
                                    </p>
                                </div>
                            </div>

                            <!-- Congenital Defect Warning -->
                            @if($delivery->congenital_defect)
                                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3 mb-3">
                                    <div class="flex items-start gap-2">
                                        <span class="text-yellow-600">⚠️</span>
                                        <div>
                                            <p class="text-xs font-semibold text-yellow-800">Kelainan Bawaan</p>
                                            <p class="text-sm text-yellow-700">{{ $delivery->congenital_defect }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- Newborn Management Checklist -->
                            <div class="border-t border-pink-200 pt-3">
                                <p class="text-xs font-semibold text-gray-600 mb-2">Manajemen Bayi Baru Lahir:</p>
                                <div class="flex flex-wrap gap-2">
                                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium {{ $delivery->imd_initiated ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                                        {{ $delivery->imd_initiated ? '✅' : '⬜' }} IMD
                                    </span>
                                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium {{ $delivery->vit_k_given ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                                        {{ $delivery->vit_k_given ? '✅' : '⬜' }} Vitamin K
                                    </span>
                                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium {{ $delivery->eye_ointment_given ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                                        {{ $delivery->eye_ointment_given ? '✅' : '⬜' }} Salep Mata
                                    </span>
                                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium {{ $delivery->hb0_given ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                                        {{ $delivery->hb0_immunization_given ? '✅' : '⬜' }} HB0
                                    </span>
                                </div>
                            </div>

                            {{-- Service Fee --}}
                            @if($delivery->service_fee)
                            <div class="border-t border-pink-200 pt-3 mt-3">
                                <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg border border-green-200">
                                    <div class="flex items-center gap-2">
                                        <x-heroicon-s-currency-dollar class="w-5 h-5 text-green-600" />
                                        <span class="text-sm font-semibold text-gray-700">Biaya Persalinan</span>
                                    </div>
                                    <span class="text-lg font-bold text-green-700">
                                        Rp {{ number_format($delivery->service_fee, 0, ',', '.') }}
                                    </span>
                                </div>
                            </div>
                            @endif
                        </div>

                        <!-- Mother's Delivery Details (Collapsible) -->
                        <details class="bg-gray-50 rounded-lg mb-4">
                            <summary class="p-3 cursor-pointer text-sm font-semibold text-gray-700 hover:bg-gray-100 rounded-lg">
                                📋 Detail Persalinan Ibu
                            </summary>
                            <div class="p-3 pt-0 grid grid-cols-2 md:grid-cols-3 gap-3 text-sm">
                                <div>
                                    <p class="text-xs text-gray-500">Lama Kala I</p>
                                    <p class="font-medium">{{ $delivery->duration_first_stage ?? '-' }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500">Lama Kala II</p>
                                    <p class="font-medium">{{ $delivery->duration_second_stage ?? '-' }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500">Keluarnya Plasenta</p>
                                    <p class="font-medium">{{ $delivery->placenta_delivery ?? '-' }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500">Robekan Perineum</p>
                                    <p class="font-medium">{{ $delivery->perineum_rupture ?? 'Tidak ada' }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500">Perdarahan</p>
                                    <p class="font-medium {{ $delivery->hasHighBleedingRisk() ? 'text-red-600 font-bold' : '' }}">
                                        {{ $delivery->bleeding_amount ?? '-' }} ml
                                    </p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500">Tekanan Darah</p>
                                    <p class="font-medium">{{ $delivery->blood_pressure ?? '-' }}</p>
                                </div>
                            </div>
                        </details>
                    @else
                        {{-- No delivery record - show info and CTA button --}}
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-4">
                            <div class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-yellow-600 flex-shrink-0 mt-0.5" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div class="flex-1">
                                    <p class="text-sm text-yellow-800 font-medium">Data persalinan detail belum tercatat</p>
                                    <p class="text-xs text-yellow-700 mt-1">Status menunjukkan "Lahir" tetapi data persalinan lengkap belum diinput</p>

                                    {{-- CTA Button: Input Persalinan --}}
                                    <div class="mt-3">
                                        <a href="{{ route('pregnancies.delivery', $pregnancy->id) }}"
                                            class="inline-flex items-center gap-2 px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white font-semibold rounded-lg transition-colors text-sm shadow-sm">
                                            <x-heroicon-o-plus class="w-4 h-4" />
                                            Input Data Persalinan
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Edit Delivery Record Button (if exists) --}}
                    @if ($pregnancy->deliveryRecord)
                        <div class="mb-4">
                            <a href="{{ route('pregnancies.delivery', $pregnancy->id) }}"
                                class="inline-flex items-center gap-2 px-4 py-2 bg-blue-100 hover:bg-blue-200 text-blue-700 font-semibold rounded-lg transition-colors text-sm">
                                <x-heroicon-o-pencil class="w-4 h-4" />
                                Edit Data Persalinan
                            </a>
                        </div>
                    @endif

                    <!-- Action Button: Add Postnatal Visit -->
                    <div class="border-t border-gray-200 pt-4 mb-4">
                        <a href="{{ route('pregnancies.postnatal', $pregnancy->id) }}"
                            class="inline-flex items-center gap-2 px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white font-semibold rounded-lg transition-colors text-sm">
                            <x-heroicon-o-plus class="w-4 h-4" />
                            Tambah Kunjungan Nifas
                        </a>
                    </div>

                    <!-- Postnatal Visits -->
                    @if ($pregnancy->postnatalVisits && $pregnancy->postnatalVisits->count() > 0)
                        <div class="border-t border-gray-200 pt-4">
                            <p class="text-sm font-semibold text-gray-700 mb-3">Kunjungan Nifas
                                ({{ $pregnancy->postnatalVisits->count() }} kali):</p>
                            <div class="space-y-2">
                                @foreach ($pregnancy->postnatalVisits->sortByDesc('visit_date') as $visit)
                                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                                        <div class="flex items-center gap-3">
                                            <span class="px-2 py-1 bg-purple-100 text-purple-800 text-xs font-semibold rounded">
                                                {{ $visit->visit_code }}
                                            </span>
                                            <div>
                                                <p class="text-sm font-medium text-gray-900">
                                                    {{ $visit->visit_date->format('d/m/Y') }}
                                                </p>
                                                <p class="text-xs text-gray-600">
                                                    Hari ke-{{ $visit->days_post_partum ?? '-' }} pasca persalinan
                                                </p>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-4">
                                            <span class="text-sm {{ $visit->td_systolic && $visit->td_systolic < 140 ? 'text-green-600' : 'text-red-600' }} font-medium">
                                                {{ $visit->td_systolic ?? '-' }}/{{ $visit->td_diastolic ?? '-' }} mmHg
                                            </span>
                                            <a href="{{ route('postnatal-visits.show', $visit->id) }}"
                                               class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-blue-700 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors border border-blue-200">
                                                <x-heroicon-m-arrow-right class="w-3 h-3" />
                                                Lihat Detail
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <p class="text-center text-gray-500 py-4">Belum ada kunjungan nifas</p>
                    @endif
                </div>
            </div>
        @endforeach
    @else
        <!-- Empty State -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
            <x-heroicon-o-sparkles class="w-16 h-16 text-gray-300 mx-auto mb-4" />
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Belum Ada Data Persalinan</h3>
            <p class="text-gray-600">Pasien ini belum memiliki riwayat persalinan yang tercatat</p>
        </div>
    @endif
</div>
