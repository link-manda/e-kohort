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
               class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                + Tambah Kunjungan
            </a>
        </div>

        @if($generalVisits->count() > 0)
            <div class="divide-y divide-gray-200">
                @foreach($generalVisits->sortByDesc('visit_date') as $visit)
                    <div class="p-4 hover:bg-gray-50 transition-colors">
                        <div class="flex items-start justify-between gap-4">
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-2">
                                    <p class="font-medium text-gray-900">
                                        {{ $visit->visit_date?->locale('id')->isoFormat('dddd, D MMMM YYYY') }}
                                    </p>
                                    @if($visit->is_emergency)
                                        <span class="inline-flex items-center px-2 py-0.5 bg-red-100 text-red-800 text-xs font-semibold rounded-full">
                                            🚨 Darurat
                                        </span>
                                    @endif
                                </div>

                                <!-- Complaint -->
                                @if($visit->complaint)
                                    <div class="mb-2">
                                        <span class="text-xs text-gray-500 font-medium">Keluhan:</span>
                                        <p class="text-sm text-gray-700">{{ Str::limit($visit->complaint, 100) }}</p>
                                    </div>
                                @endif

                                <!-- Diagnosis -->
                                @if($visit->diagnosis)
                                    <div class="mb-2">
                                        <span class="text-xs text-gray-500 font-medium">Diagnosis:</span>
                                        <p class="text-sm text-gray-900 font-medium">
                                            {{ $visit->diagnosis }}
                                            @if($visit->icd10_code)
                                                <span class="text-gray-500">({{ $visit->icd10_code }})</span>
                                            @endif
                                        </p>
                                    </div>
                                @endif

                                <!-- Vital Signs Summary -->
                                <div class="flex flex-wrap gap-3 mt-2">
                                    @if($visit->temperature)
                                        <span class="inline-flex items-center gap-1 text-xs text-gray-600 bg-gray-100 px-2 py-1 rounded">
                                            🌡️ {{ number_format($visit->temperature, 1) }}°C
                                        </span>
                                    @endif
                                    @if($visit->weight)
                                        <span class="inline-flex items-center gap-1 text-xs text-gray-600 bg-gray-100 px-2 py-1 rounded">
                                            ⚖️ {{ number_format($visit->weight, 1) }} kg
                                        </span>
                                    @endif
                                    @if($visit->prescriptions && $visit->prescriptions->count() > 0)
                                        <span class="inline-flex items-center gap-1 text-xs text-blue-600 bg-blue-100 px-2 py-1 rounded">
                                            💊 {{ $visit->prescriptions->count() }} obat
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="text-right flex-shrink-0">
                                <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full
                                    {{ $visit->status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ $visit->status === 'completed' ? 'Selesai' : 'Aktif' }}
                                </span>
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
