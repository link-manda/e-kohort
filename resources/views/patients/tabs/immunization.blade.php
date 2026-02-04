<!-- Riwayat Imunisasi Anak -->
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
            <x-heroicon-o-shield-check class="w-6 h-6 text-orange-600" />
            Riwayat Imunisasi & Tumbuh Kembang
        </h3>
        @if ($children && $children->count() > 0)
            <a href="{{ route('children.immunization', $children->first()->id) }}"
                class="inline-flex items-center gap-2 px-4 py-2 bg-orange-600 hover:bg-orange-700 text-white font-semibold rounded-lg transition-colors text-sm">
                <x-heroicon-o-plus class="w-4 h-4" />
                Tambah Imunisasi
            </a>
        @else
            <a href="{{ route('children.register') }}?patient_id={{ $patient->id }}"
                class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-colors text-sm">
                <x-heroicon-o-plus class="w-4 h-4" />
                Daftarkan Anak
            </a>
        @endif
    </div>

    @if ($children && $children->count() > 0)
        @foreach ($children as $child)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <!-- Child Header -->
                <div class="bg-gradient-to-r from-orange-500 to-orange-600 p-4 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <h4 class="font-bold text-lg">{{ $child->name }}</h4>
                            <p class="text-sm opacity-90">
                                {{ $child->gender === 'L' ? '👦 Laki-laki' : '👧 Perempuan' }} |
                                {{ $child->dob->diffInMonths(now()) }} bulan
                            </p>
                        </div>
                        @if ($child->nik)
                            <span class="px-3 py-1 bg-white/20 backdrop-blur rounded-full text-xs font-semibold">
                                NIK: {{ $child->nik }}
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Child Info -->
                <div class="p-4">
                    <!-- Statistics -->
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-4">
                        <div class="text-center p-3 bg-blue-50 rounded-lg">
                            <p class="text-xs text-blue-600 font-medium mb-1">Kunjungan</p>
                            <p class="text-lg font-bold text-blue-900">{{ $child->childVisits->count() }}</p>
                        </div>
                        <div class="text-center p-3 bg-green-50 rounded-lg">
                            <p class="text-xs text-green-600 font-medium mb-1">Imunisasi</p>
                            <p class="text-lg font-bold text-green-900">{{ $child->immunizationActions->count() }}</p>
                        </div>
                    </div>

                    <!-- Latest Immunizations -->
                    @if ($child->immunizationActions && $child->immunizationActions->count() > 0)
                        <div class="border-t border-gray-200 pt-4">
                            <p class="text-sm font-semibold text-gray-700 mb-3">Riwayat Imunisasi Terbaru:</p>
                            <div class="space-y-2">
                                @foreach ($child->immunizationActions->sortByDesc('immunization_date')->take(5) as $immunization)
                                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                        <div class="flex items-center gap-3">
                                            <span
                                                class="px-2 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded">
                                                {{ $immunization->vaccine->name ?? 'Vaksin' }}
                                            </span>
                                            <div>
                                                <p class="text-sm font-medium text-gray-900">
                                                    {{ $immunization->immunization_date->format('d/m/Y') }}
                                                </p>
                                                <p class="text-xs text-gray-600">
                                                    Usia: {{ $immunization->age_at_immunization ?? '-' }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <p class="text-center text-gray-500 py-4">Belum ada riwayat imunisasi</p>
                    @endif
                </div>
            </div>
        @endforeach
    @else
        <!-- Empty State -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
            <x-heroicon-o-shield-check class="w-16 h-16 text-gray-300 mx-auto mb-4" />
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Belum Ada Data Anak</h3>
            <p class="text-gray-600 mb-6">Pasien ini belum terdaftar sebagai anak untuk layanan imunisasi</p>
            <a href="{{ route('children.register') }}?patient_id={{ $patient->id }}"
                class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-colors">
                <x-heroicon-o-plus class="w-5 h-5" />
                Daftarkan sebagai Anak
            </a>
        </div>
    @endif
</div>
