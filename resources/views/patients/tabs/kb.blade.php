<!-- Riwayat KB (Keluarga Berencana) -->
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
            <x-heroicon-o-check-circle class="w-6 h-6 text-green-600" />
            Riwayat KB (Keluarga Berencana)
        </h3>
        <a href="{{ route('kb.entry') }}?patient_id={{ $patient->id }}"
            class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg transition-colors text-sm">
            <x-heroicon-o-plus class="w-4 h-4" />
            Tambah Kunjungan KB
        </a>
    </div>

    @if ($kbVisits && $kbVisits->count() > 0)
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <!-- Statistics -->
            <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-6">
                <div class="text-center p-4 bg-green-50 rounded-lg">
                    <p class="text-xs text-green-600 font-medium mb-1">Total Kunjungan</p>
                    <p class="text-2xl font-bold text-green-900">{{ $kbVisits->count() }}</p>
                </div>
                @php
                    $latestKb = $kbVisits->first();
                @endphp
                @if ($latestKb)
                    <div class="text-center p-4 bg-blue-50 rounded-lg">
                        <p class="text-xs text-blue-600 font-medium mb-1">Metode Aktif</p>
                        <p class="text-sm font-bold text-blue-900">{{ $latestKb->kbMethod->name ?? '-' }}</p>
                    </div>
                    <div class="text-center p-4 bg-purple-50 rounded-lg">
                        <p class="text-xs text-purple-600 font-medium mb-1">Kunjungan Terakhir</p>
                        <p class="text-sm font-bold text-purple-900">{{ $latestKb->visit_date->format('d/m/Y') }}</p>
                    </div>
                @endif
            </div>

            <!-- KB Visits List -->
            <div class="space-y-3">
                <p class="text-sm font-semibold text-gray-700 mb-3">Riwayat Kunjungan:</p>
                @foreach ($kbVisits as $visit)
                    <div class="border border-gray-200 rounded-lg p-4 hover:border-green-300 transition-colors">
                        <div class="flex items-start justify-between mb-2">
                            <div>
                                <p class="text-sm font-semibold text-gray-900">{{ $visit->visit_date->format('d/m/Y') }}
                                </p>
                                <p class="text-xs text-gray-600">Metode: <span
                                        class="font-medium">{{ $visit->kbMethod->name ?? '-' }}</span></p>
                            </div>
                            @if ($visit->side_effects)
                                <span class="px-2 py-1 bg-yellow-100 text-yellow-800 text-xs font-semibold rounded">
                                    Ada Efek Samping
                                </span>
                            @endif
                        </div>
                        @if ($visit->complaint)
                            <p class="text-sm text-gray-700">Keluhan: {{ $visit->complaint }}</p>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    @else
        <!-- Empty State -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
            <x-heroicon-o-check-circle class="w-16 h-16 text-gray-300 mx-auto mb-4" />
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Belum Ada Riwayat KB</h3>
            <p class="text-gray-600 mb-6">Pasien ini belum pernah menggunakan layanan KB</p>
            <a href="{{ route('kb.entry') }}?patient_id={{ $patient->id }}"
                class="inline-flex items-center gap-2 px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg transition-colors">
                <x-heroicon-o-plus class="w-5 h-5" />
                Daftarkan Layanan KB
            </a>
        </div>
    @endif
</div>
