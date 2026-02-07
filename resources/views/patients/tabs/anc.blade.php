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
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-4">
                        @if ($pregnancy->status === 'Aktif')
                            <div class="text-center p-3 bg-purple-50 rounded-lg">
                                <p class="text-xs text-purple-600 font-medium mb-1">Usia Kehamilan</p>
                                <p class="text-lg font-bold text-purple-900">{{ $pregnancy->gestational_age }} minggu
                                </p>
                            </div>
                        @endif
                        <div class="text-center p-3 bg-blue-50 rounded-lg">
                            <p class="text-xs text-blue-600 font-medium mb-1">Total Kunjungan ANC</p>
                            <p class="text-lg font-bold text-blue-900">{{ $pregnancy->ancVisits->count() }} kali</p>
                        </div>
                    </div>

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
                        <div class="border-t border-gray-200 pt-4">
                            <p class="text-sm font-semibold text-gray-700 mb-3">Riwayat Kunjungan ANC:</p>
                            <div class="space-y-2">
                                @foreach ($pregnancy->ancVisits->sortByDesc('visit_date')->take(5) as $visit)
                                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                                        <div class="flex items-center gap-3">
                                            <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs font-semibold rounded">
                                                {{ $visit->visit_code }}
                                            </span>
                                            <div>
                                                <p class="text-sm font-medium text-gray-900">
                                                    {{ $visit->visit_date->format('d/m/Y') }}
                                                </p>
                                                <p class="text-xs text-gray-600">
                                                    UK {{ $visit->gestational_age ?? '-' }} minggu
                                                </p>
                                            </div>
                                        </div>

                                        <!-- Vital Signs -->
                                        <div class="flex items-center gap-4">
                                            @if($visit->td_systolic && $visit->td_diastolic)
                                                <div class="text-center">
                                                    <p class="text-xs text-gray-500">TD</p>
                                                    <p class="text-sm font-semibold {{ ($visit->td_systolic >= 140 || $visit->td_diastolic >= 90) ? 'text-red-600' : 'text-green-600' }}">
                                                        {{ $visit->td_systolic }}/{{ $visit->td_diastolic }}
                                                    </p>
                                                </div>
                                            @endif
                                            @if($visit->weight)
                                                <div class="text-center">
                                                    <p class="text-xs text-gray-500">BB</p>
                                                    <p class="text-sm font-semibold text-gray-700">{{ $visit->weight }} kg</p>
                                                </div>
                                            @endif
                                            <a href="{{ route('anc-visits.show', $visit->id) }}"
                                                class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                                                Detail →
                                            </a>
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
