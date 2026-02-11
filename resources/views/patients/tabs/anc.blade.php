<!-- Riwayat Kehamilan/ANC -->
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
            <x-heroicon-o-heart class="w-6 h-6 text-pink-600" />
            Riwayat Kehamilan & Kunjungan ANC
        </h3>
        @if ($patient->activePregnancy && $patient->activePregnancy->status === 'Aktif')
            <a href="{{ route('anc-visits.create', $patient->activePregnancy->id) }}"
                class="inline-flex items-center gap-2 px-4 py-2 bg-pink-600 hover:bg-pink-700 text-white font-semibold rounded-lg transition-colors text-sm">
                <x-heroicon-o-plus class="w-4 h-4" />
                Tambah Kunjungan ANC
            </a>
        @elseif(!$patient->activePregnancy)
            <a href="{{ route('pregnancies.create', $patient->id) }}"
                class="inline-flex items-center gap-2 px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white font-semibold rounded-lg transition-colors text-sm">
                <x-heroicon-o-plus class="w-4 h-4" />
                Daftarkan Kehamilan
            </a>
        @endif
    </div>

    @if ($patient->pregnancies && $patient->pregnancies->count() > 0)
        @foreach ($patient->pregnancies->sortByDesc('hpht') as $pregnancy)
            <div
                class="bg-white rounded-xl shadow-sm border-2 {{ $pregnancy->status === 'Aktif' ? 'border-green-500' : 'border-gray-200' }} overflow-hidden">
                <!-- Pregnancy Header -->
                <div
                    class="bg-gradient-to-r {{ $pregnancy->status === 'Aktif' ? 'from-green-500 to-green-600' : 'from-gray-500 to-gray-600' }} p-4 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <h4 class="font-bold text-lg">
                                Kehamilan {{ $pregnancy->gravida }}
                                (G{{ $pregnancy->gravida }}P{{ $pregnancy->para }}A{{ $pregnancy->abortus }})
                            </h4>
                            <p class="text-sm opacity-90">
                                HPHT: {{ $pregnancy->hpht->format('d/m/Y') }} | HPL:
                                {{ $pregnancy->hpl->format('d/m/Y') }}
                            </p>
                        </div>
                        <span class="px-3 py-1 bg-white/20 backdrop-blur rounded-full text-sm font-semibold">
                            {{ $pregnancy->status }}
                        </span>
                    </div>
                </div>

                <!-- Pregnancy Info -->
                <div class="p-4">
                    {{-- KONDISI TERKINI (DASHBOARD) --}}
                    @php
                        $latestVisit = $pregnancy->ancVisits->sortByDesc('visit_date')->first();
                    @endphp

                    @if ($latestVisit)
                        <div class="mb-6 border rounded-xl overflow-hidden shadow-sm">
                            <div class="bg-gray-50 px-4 py-3 border-b flex justify-between items-center">
                                <h5 class="font-bold text-gray-800 flex items-center gap-2">
                                    <x-heroicon-o-chart-bar class="w-5 h-5 text-pink-600" />
                                    Kondisi Terkini ({{ $latestVisit->visit_date->format('d/m/Y') }})
                                </h5>
                                <span class="text-xs font-medium px-2 py-1 rounded bg-white border text-gray-600">
                                    UK: {{ $latestVisit->gestational_age }} minggu
                                </span>
                            </div>

                            <div class="p-4 bg-white grid grid-cols-1 md:grid-cols-3 gap-4">
                                {{-- 1. Tanda Vital Ibu --}}
                                <div class="space-y-3">
                                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Vital Signs</p>
                                    <div class="grid grid-cols-2 gap-2">
                                        <div class="p-2 bg-pink-50 rounded-lg text-center">
                                            <p class="text-[10px] text-pink-600 mb-1">Tekanan Darah</p>
                                            <p class="text-lg font-bold text-gray-800">
                                                {{ $latestVisit->systolic }}/{{ $latestVisit->diastolic }}
                                            </p>
                                            <p class="text-[10px] text-gray-500">mmHg</p>
                                        </div>
                                        <div class="p-2 {{ $latestVisit->map_score > 90 ? 'bg-red-50' : 'bg-green-50' }} rounded-lg text-center">
                                            <p class="text-[10px] {{ $latestVisit->map_score > 90 ? 'text-red-600' : 'text-green-600' }} mb-1">MAP</p>
                                            <p class="text-lg font-bold text-gray-800">{{ $latestVisit->map_score }}</p>
                                            <span class="text-[10px] px-1.5 py-0.5 rounded-full {{ $latestVisit->map_score > 90 ? 'bg-red-200 text-red-800' : 'bg-green-200 text-green-800' }}">
                                                {{ $latestVisit->map_score > 90 ? 'Waspada' : 'Normal' }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="flex justify-between items-center text-sm border-t pt-2 mt-2">
                                        <span class="text-gray-500">Berat Badan:</span>
                                        <span class="font-semibold">{{ $latestVisit->weight }} kg</span>
                                    </div>
                                    <div class="flex justify-between items-center text-sm">
                                        <span class="text-gray-500">IMT:</span>
                                        <span class="font-semibold">{{ $latestVisit->bmi }}</span>
                                    </div>
                                </div>

                                {{-- 2. Kondisi Janin --}}
                                <div class="space-y-3 md:border-l md:pl-4">
                                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Kondisi Janin</p>
                                    <div class="grid grid-cols-2 gap-2">
                                        <div class="p-2 bg-blue-50 rounded-lg text-center">
                                            <p class="text-[10px] text-blue-600 mb-1">DJJ</p>
                                            <p class="text-lg font-bold text-gray-800">{{ $latestVisit->djj ?? '-' }}</p>
                                            <p class="text-[10px] text-gray-500">bpm</p>
                                        </div>
                                        <div class="p-2 bg-purple-50 rounded-lg text-center">
                                            <p class="text-[10px] text-purple-600 mb-1">TFU</p>
                                            <p class="text-lg font-bold text-gray-800">{{ $latestVisit->tfu ?? '-' }}</p>
                                            <p class="text-[10px] text-gray-500">cm</p>
                                        </div>
                                    </div>
                                    <div class="text-sm mt-2">
                                        <p class="text-gray-500 mb-1">Presentasi/Letak:</p>
                                        <p class="font-medium text-gray-800 bg-gray-100 px-2 py-1 rounded inline-block">
                                            {{ $latestVisit->fetal_presentation ?? '-' }}
                                        </p>
                                    </div>
                                </div>

                                {{-- 3. Status & Risiko --}}
                                <div class="space-y-3 md:border-l md:pl-4">
                                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Status & Risiko</p>

                                    {{-- Risk Badge --}}
                                    <div class="flex items-center justify-between p-2 rounded-lg {{ $latestVisit->risk_category == 'Tinggi' || $latestVisit->risk_category == 'Ekstrem' ? 'bg-red-50 border border-red-100' : 'bg-green-50 border border-green-100' }}">
                                        <span class="text-sm font-medium {{ $latestVisit->risk_category == 'Tinggi' || $latestVisit->risk_category == 'Ekstrem' ? 'text-red-700' : 'text-green-700' }}">
                                            Resiko {{ $latestVisit->risk_category }}
                                        </span>
                                        @if($latestVisit->risk_category == 'Tinggi' || $latestVisit->risk_category == 'Ekstrem')
                                            <x-heroicon-s-exclamation-triangle class="w-5 h-5 text-red-500"/>
                                        @else
                                            <x-heroicon-s-check-circle class="w-5 h-5 text-green-500"/>
                                        @endif
                                    </div>

                                    <div class="space-y-1 mt-2">
                                        {{-- Alert Anemia --}}
                                        @if($latestVisit->hb && $latestVisit->hb < 11)
                                            <div class="flex items-center gap-2 text-xs text-red-600 bg-red-50 px-2 py-1 rounded">
                                                <span class="w-2 h-2 rounded-full bg-red-500"></span>
                                                Anemia (Hb: {{ $latestVisit->hb }})
                                            </div>
                                        @endif

                                        {{-- Alert KEK --}}
                                        @if($latestVisit->lila && $latestVisit->lila < 23.5)
                                            <div class="flex items-center gap-2 text-xs text-orange-600 bg-orange-50 px-2 py-1 rounded">
                                                <span class="w-2 h-2 rounded-full bg-orange-500"></span>
                                                KEK (LILA: {{ $latestVisit->lila }})
                                            </div>
                                        @endif

                                        {{-- Alert Protein Urine --}}
                                        @if($latestVisit->protein_urine && $latestVisit->protein_urine != 'Negatif')
                                            <div class="flex items-center gap-2 text-xs text-yellow-700 bg-yellow-50 px-2 py-1 rounded">
                                                <span class="w-2 h-2 rounded-full bg-yellow-500"></span>
                                                Protein Urine: {{ $latestVisit->protein_urine }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="bg-blue-50 border border-blue-100 rounded-lg p-4 mb-6 flex items-start gap-3">
                            <x-heroicon-o-information-circle class="w-5 h-5 text-blue-600 mt-0.5" />
                            <div>
                                <h5 class="text-sm font-bold text-blue-800">Belum Ada Kunjungan</h5>
                                <p class="text-sm text-blue-600 mt-1">
                                    Lakukan kunjungan pertama untuk melihat dashboard kondisi kehamilan.
                                </p>
                            </div>
                        </div>
                    @endif

                    {{-- Delivery Readiness Alert --}}
                    @if ($pregnancy->status === 'Aktif' && $pregnancy->gestational_age >= 37 && !$pregnancy->hasDeliveryRecord())
                        <div class="bg-green-50 border-l-4 border-green-500 rounded-lg p-4 mb-4">
                            <div class="flex items-start gap-3">
                                <x-heroicon-o-check-circle class="w-5 h-5 text-green-600 flex-shrink-0 mt-0.5" />
                                <div class="flex-1">
                                    <p class="text-sm text-green-800 font-medium">
                                        ✅ Kehamilan Aterm ({{ $pregnancy->gestational_age }} minggu)
                                    </p>
                                    <p class="text-xs text-green-700 mt-1">
                                        Pasien siap melahirkan. Input data persalinan saat pasien melahirkan.
                                    </p>

                                    {{-- CTA: Input Persalinan --}}
                                    <div class="mt-3">
                                        <a href="{{ route('pregnancies.delivery', $pregnancy->id) }}"
                                            class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg transition-colors text-sm shadow-sm">
                                            <x-heroicon-o-sparkles class="w-4 h-4" />
                                            Siap Input Data Persalinan
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- ANC Visits List -->
                    @if ($pregnancy->ancVisits && $pregnancy->ancVisits->count() > 0)
                        <div class="border-t border-gray-200 pt-6">
                            <h5 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                                <x-heroicon-o-clock class="w-5 h-5 text-gray-500"/>
                                Riwayat Kunjungan ANC
                            </h5>

                            <div class="space-y-4">
                                @foreach ($pregnancy->ancVisits->sortByDesc('visit_date') as $visit)
                                    <div class="bg-white border rounded-xl p-4 hover:shadow-md transition-shadow relative overflow-hidden group">
                                        {{-- Decorative Side Bar based on Risk --}}
                                        <div class="absolute left-0 top-0 bottom-0 w-1 {{ $visit->risk_category == 'Tinggi' || $visit->risk_category == 'Ekstrem' ? 'bg-red-500' : 'bg-green-500' }}"></div>

                                        <div class="pl-3 flex flex-col md:flex-row gap-4 justify-between">
                                            {{-- Column 1: Header Info --}}
                                            <div class="md:w-1/4">
                                                <div class="flex items-center gap-2 mb-1">
                                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-gray-800 text-white">
                                                        {{ $visit->visit_code }}
                                                    </span>
                                                    <span class="text-sm font-bold text-gray-800">
                                                        {{ $visit->visit_date->format('d/m/Y') }}
                                                    </span>
                                                </div>
                                                <p class="text-xs text-gray-500 mb-2">
                                                    UK: <span class="font-medium text-gray-700">{{ $visit->gestational_age }} minggu</span>
                                                </p>
                                                <div class="flex items-center gap-1.5 text-xs text-gray-500">
                                                    <x-heroicon-o-user class="w-3 h-3"/>
                                                    {{ $visit->midwife_name ?? '-' }}
                                                </div>
                                            </div>

                                            {{-- Column 2: Vitals & Fizik --}}
                                            <div class="md:w-1/4 grid grid-cols-2 gap-y-2 text-sm border-l pl-4 border-dashed">
                                                <div>
                                                    <p class="text-[10px] text-gray-400">Tekanan Darah</p>
                                                    <p class="font-semibold {{ ($visit->systolic >= 140 || $visit->diastolic >= 90) ? 'text-red-600' : 'text-gray-800' }}">
                                                        {{ $visit->systolic }}/{{ $visit->diastolic }}
                                                    </p>
                                                </div>
                                                <div>
                                                    <p class="text-[10px] text-gray-400">Berat Badan</p>
                                                    <p class="font-semibold text-gray-800">{{ $visit->weight }} kg</p>
                                                </div>
                                                <div>
                                                    <p class="text-[10px] text-gray-400">MAP</p>
                                                    <p class="font-semibold {{ $visit->map_score > 90 ? 'text-red-600' : 'text-gray-800' }}">
                                                        {{ $visit->map_score }}
                                                    </p>
                                                </div>
                                                <div>
                                                    <p class="text-[10px] text-gray-400">LILA</p>
                                                    <p class="font-semibold {{ $visit->lila < 23.5 ? 'text-orange-600' : 'text-gray-800' }}">
                                                        {{ $visit->lila ?? '-' }} cm
                                                    </p>
                                                </div>
                                            </div>

                                            {{-- Column 3: Janin & Lab --}}
                                            <div class="md:w-1/4 grid grid-cols-2 gap-y-2 text-sm border-l pl-4 border-dashed">
                                                <div>
                                                    <p class="text-[10px] text-gray-400">DJJ</p>
                                                    <p class="font-semibold text-gray-800">{{ $visit->djj ?? '-' }} bpm</p>
                                                </div>
                                                <div>
                                                    <p class="text-[10px] text-gray-400">TFU</p>
                                                    <p class="font-semibold text-gray-800">{{ $visit->tfu ?? '-' }} cm</p>
                                                </div>
                                                <div class="col-span-2">
                                                    <p class="text-[10px] text-gray-400">Lab/Risiko</p>
                                                    <div class="flex flex-wrap gap-1 mt-0.5">
                                                        @if($visit->hb && $visit->hb < 11)
                                                            <span class="px-1.5 py-0.5 bg-red-100 text-red-700 text-[10px] rounded">Anemia</span>
                                                        @endif
                                                        @if($visit->protein_urine && $visit->protein_urine != 'Negatif')
                                                            <span class="px-1.5 py-0.5 bg-yellow-100 text-yellow-700 text-[10px] rounded">Prot+</span>
                                                        @endif
                                                        @if(!$visit->hasAnemia() && $visit->protein_urine == 'Negatif')
                                                            <span class="text-gray-500 italic text-xs">-</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Column 4: Fees & Action --}}
                                            <div class="md:w-1/4 flex flex-col justify-between items-end border-l pl-4 border-dashed">
                                                <div class="text-right">
                                                    <p class="text-[10px] text-gray-400 uppercase">Biaya Layanan</p>
                                                    <p class="text-lg font-bold text-green-600">
                                                        Rp {{ number_format($visit->service_fee ?? 0, 0, ',', '.') }}
                                                    </p>
                                                </div>

                                                <a href="{{ route('anc-visits.show', $visit->id) }}"
                                                   class="inline-flex items-center gap-1 text-sm font-medium text-blue-600 hover:text-blue-700 hover:underline mt-2">
                                                    Lihat Detail
                                                    <x-heroicon-m-arrow-right class="w-4 h-4"/>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <p class="text-center text-gray-500 py-4">Belum ada kunjungan ANC</p>
                    @endif
                </div>
            </div>
        @endforeach
    @else
        <!-- Empty State -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
            <x-heroicon-o-heart class="w-16 h-16 text-gray-300 mx-auto mb-4" />
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Belum Ada Data Kehamilan</h3>
            <p class="text-gray-600 mb-6">Pasien ini belum pernah terdaftar untuk pemeriksaan kehamilan (ANC)</p>
            <a href="{{ route('pregnancies.create', $patient->id) }}"
                class="inline-flex items-center gap-2 px-6 py-3 bg-purple-600 hover:bg-purple-700 text-white font-semibold rounded-lg transition-colors">
                <x-heroicon-o-plus class="w-5 h-5" />
                Daftarkan Kehamilan Baru
            </a>
        </div>
    @endif
</div>
