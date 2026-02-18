@php
    $growthRecords = $child->growthRecords ?? collect();
    $latestGrowth = $growthRecords->first();

    // Check for nutritional alerts
    $hasStunting = $latestGrowth && $latestGrowth->is_stunting;
    $hasWasting = $latestGrowth && $latestGrowth->is_wasting;
    $hasUnderweight = $latestGrowth && $latestGrowth->is_underweight;
    $hasAlert = $hasStunting || $hasWasting || $hasUnderweight;
@endphp

<div class="space-y-6">

    <!-- Nutritional Alert Banner -->
    @if($hasAlert)
        <div class="bg-red-50 border-2 border-red-300 rounded-xl p-4 animate-pulse-slow">
            <div class="flex items-start gap-3">
                <div class="flex-shrink-0">
                    <x-heroicon-s-exclamation-triangle class="w-6 h-6 text-red-600" />
                </div>
                <div>
                    <h4 class="font-bold text-red-800 text-sm">⚠️ Peringatan Status Gizi</h4>
                    <p class="text-sm text-red-700 mt-1">
                        Berdasarkan data pertumbuhan terakhir
                        ({{ $latestGrowth->record_date?->locale('id')->isoFormat('D MMM YYYY') }}),
                        anak ini terindikasi:
                    </p>
                    <div class="flex flex-wrap gap-2 mt-2">
                        @if($hasStunting)
                            <span class="inline-flex items-center px-3 py-1 bg-red-200 text-red-900 text-xs font-bold rounded-full">
                                📏 Stunting (TB/U: {{ number_format($latestGrowth->zscore_tb_u, 2) }})
                            </span>
                        @endif
                        @if($hasWasting)
                            <span class="inline-flex items-center px-3 py-1 bg-red-200 text-red-900 text-xs font-bold rounded-full">
                                ⚖️ Wasting (BB/TB: {{ number_format($latestGrowth->zscore_bb_tb, 2) }})
                            </span>
                        @endif
                        @if($hasUnderweight)
                            <span class="inline-flex items-center px-3 py-1 bg-red-200 text-red-900 text-xs font-bold rounded-full">
                                📉 Underweight (BB/U: {{ number_format($latestGrowth->zscore_bb_u, 2) }})
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Latest Growth Summary -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                <x-heroicon-o-chart-bar class="w-5 h-5 text-purple-600" />
                Data Pertumbuhan Terakhir
            </h3>
            <a href="{{ route('children.growth', $child->id) }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white font-semibold rounded-lg transition-colors text-sm">
                <x-heroicon-o-plus class="w-4 h-4" />
                Tambah Data
            </a>
        </div>

        @if($latestGrowth)
            <!-- Measurement Cards -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 text-center">
                    <p class="text-xs text-gray-500 mb-1">⚖️ Berat Badan</p>
                    <p class="text-2xl font-bold text-blue-600">{{ number_format($latestGrowth->weight, 1) }}</p>
                    <p class="text-xs text-gray-500">kg</p>
                </div>
                <div class="bg-green-50 border border-green-200 rounded-lg p-4 text-center">
                    <p class="text-xs text-gray-500 mb-1">📏 Tinggi Badan</p>
                    <p class="text-2xl font-bold text-green-600">{{ number_format($latestGrowth->height, 1) }}</p>
                    <p class="text-xs text-gray-500">cm</p>
                </div>
                @if($latestGrowth->head_circumference)
                    <div class="bg-purple-50 border border-purple-200 rounded-lg p-4 text-center">
                        <p class="text-xs text-gray-500 mb-1">🧠 Lingkar Kepala</p>
                        <p class="text-2xl font-bold text-purple-600">{{ number_format($latestGrowth->head_circumference, 1) }}</p>
                        <p class="text-xs text-gray-500">cm</p>
                    </div>
                @endif
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 text-center">
                    <p class="text-xs text-gray-500 mb-1">📅 Tanggal Ukur</p>
                    <p class="text-sm font-bold text-gray-700">{{ $latestGrowth->record_date?->locale('id')->isoFormat('D MMM YY') }}</p>
                    <p class="text-xs text-gray-500">{{ $child->getAgeAtVisit($latestGrowth->record_date) }}</p>
                </div>
            </div>

            <!-- Z-Score Status Cards -->
            @if($latestGrowth->zscore_bb_u !== null || $latestGrowth->zscore_tb_u !== null || $latestGrowth->zscore_bb_tb !== null)
                <p class="text-xs font-semibold text-gray-500 uppercase mb-3">Status Gizi (Z-Score)</p>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-6">
                    <!-- BB/U (Weight-for-Age) -->
                    @if($latestGrowth->zscore_bb_u !== null)
                        @php
                            $bbuColor = $latestGrowth->is_underweight ? 'red' : ($latestGrowth->zscore_bb_u < -1 ? 'yellow' : 'green');
                        @endphp
                        <div class="bg-{{ $bbuColor }}-50 border border-{{ $bbuColor }}-200 rounded-lg p-4">
                            <div class="flex items-center justify-between mb-1">
                                <p class="text-xs font-semibold text-gray-600">BB/U (Berat/Umur)</p>
                                <span class="text-xs font-mono font-bold text-{{ $bbuColor }}-700">{{ number_format($latestGrowth->zscore_bb_u, 2) }}</span>
                            </div>
                            <p class="text-sm font-bold text-{{ $bbuColor }}-700">
                                {{ $latestGrowth->status_bb_u ?? ($latestGrowth->is_underweight ? 'Underweight' : 'Normal') }}
                            </p>
                            @if($latestGrowth->is_underweight)
                                <p class="text-xs text-red-600 mt-1">⚠️ Di bawah -2 SD</p>
                            @endif
                        </div>
                    @endif

                    <!-- TB/U (Height-for-Age) -->
                    @if($latestGrowth->zscore_tb_u !== null)
                        @php
                            $tbuColor = $latestGrowth->is_stunting ? 'red' : ($latestGrowth->zscore_tb_u < -1 ? 'yellow' : 'green');
                        @endphp
                        <div class="bg-{{ $tbuColor }}-50 border border-{{ $tbuColor }}-200 rounded-lg p-4">
                            <div class="flex items-center justify-between mb-1">
                                <p class="text-xs font-semibold text-gray-600">TB/U (Tinggi/Umur)</p>
                                <span class="text-xs font-mono font-bold text-{{ $tbuColor }}-700">{{ number_format($latestGrowth->zscore_tb_u, 2) }}</span>
                            </div>
                            <p class="text-sm font-bold text-{{ $tbuColor }}-700">
                                {{ $latestGrowth->status_tb_u ?? ($latestGrowth->is_stunting ? 'Stunting' : 'Normal') }}
                            </p>
                            @if($latestGrowth->is_stunting)
                                <p class="text-xs text-red-600 mt-1">⚠️ Di bawah -2 SD</p>
                            @endif
                        </div>
                    @endif

                    <!-- BB/TB (Weight-for-Height) -->
                    @if($latestGrowth->zscore_bb_tb !== null)
                        @php
                            $bbtbColor = $latestGrowth->is_wasting ? 'red' : ($latestGrowth->zscore_bb_tb < -1 ? 'yellow' : 'green');
                        @endphp
                        <div class="bg-{{ $bbtbColor }}-50 border border-{{ $bbtbColor }}-200 rounded-lg p-4">
                            <div class="flex items-center justify-between mb-1">
                                <p class="text-xs font-semibold text-gray-600">BB/TB (Berat/Tinggi)</p>
                                <span class="text-xs font-mono font-bold text-{{ $bbtbColor }}-700">{{ number_format($latestGrowth->zscore_bb_tb, 2) }}</span>
                            </div>
                            <p class="text-sm font-bold text-{{ $bbtbColor }}-700">
                                {{ $latestGrowth->status_bb_tb ?? ($latestGrowth->is_wasting ? 'Wasting' : 'Normal') }}
                            </p>
                            @if($latestGrowth->is_wasting)
                                <p class="text-xs text-red-600 mt-1">⚠️ Di bawah -2 SD</p>
                            @endif
                        </div>
                    @endif
                </div>
            @endif

            <!-- Supplement Indicators -->
            @if($latestGrowth->vitamin_a || $latestGrowth->deworming_medicine || $latestGrowth->pmt_given)
                <p class="text-xs font-semibold text-gray-500 uppercase mb-3">Suplemen & Intervensi</p>
                <div class="flex flex-wrap gap-3 mb-4">
                    @if($latestGrowth->vitamin_a)
                        <div class="inline-flex items-center gap-2 px-3 py-2 bg-orange-50 border border-orange-200 rounded-lg text-sm">
                            <span class="text-lg">💊</span>
                            <div>
                                <p class="font-semibold text-orange-800 text-xs">Vitamin A</p>
                                <p class="text-xs text-orange-600">{{ $latestGrowth->vitamin_a }}</p>
                            </div>
                        </div>
                    @endif
                    <div class="inline-flex items-center gap-2 px-3 py-2 {{ $latestGrowth->deworming_medicine ? 'bg-green-50 border-green-200' : 'bg-gray-50 border-gray-200' }} border rounded-lg text-sm">
                        <span class="text-lg">🪱</span>
                        <div>
                            <p class="font-semibold {{ $latestGrowth->deworming_medicine ? 'text-green-800' : 'text-gray-500' }} text-xs">Obat Cacing</p>
                            <p class="text-xs {{ $latestGrowth->deworming_medicine ? 'text-green-600' : 'text-gray-400' }}">
                                {{ $latestGrowth->deworming_medicine ? '✅ Diberikan' : '❌ Tidak' }}
                            </p>
                        </div>
                    </div>
                    <div class="inline-flex items-center gap-2 px-3 py-2 {{ $latestGrowth->pmt_given ? 'bg-blue-50 border-blue-200' : 'bg-gray-50 border-gray-200' }} border rounded-lg text-sm">
                        <span class="text-lg">🍲</span>
                        <div>
                            <p class="font-semibold {{ $latestGrowth->pmt_given ? 'text-blue-800' : 'text-gray-500' }} text-xs">PMT</p>
                            <p class="text-xs {{ $latestGrowth->pmt_given ? 'text-blue-600' : 'text-gray-400' }}">
                                {{ $latestGrowth->pmt_given ? '✅ Diberikan' : '❌ Tidak' }}
                            </p>
                        </div>
                    </div>
                </div>
            @endif

        @else
            <div class="text-center py-6 text-gray-500">
                <x-heroicon-o-scale class="w-12 h-12 mx-auto mb-3 text-gray-300" />
                <p class="font-medium">Belum ada data pertumbuhan</p>
                <p class="text-sm mt-1">Tambahkan data berat dan tinggi badan anak</p>
            </div>
        @endif
    </div>

    <!-- Growth History Timeline -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                <x-heroicon-o-arrow-trending-up class="w-5 h-5 text-green-600" />
                Riwayat Pertumbuhan
            </h3>
        </div>

        @if($growthRecords->count() > 0)
            <div class="divide-y divide-gray-200">
                @foreach($growthRecords->sortByDesc('record_date') as $record)
                    <div class="hover:bg-gray-50/50 transition-colors" x-data="{ expanded: false }">
                        <!-- Row Header (always visible) -->
                        <div class="px-6 py-4 flex items-center gap-4 cursor-pointer" @click="expanded = !expanded">
                            <!-- Date & Age -->
                            <div class="w-32 flex-shrink-0">
                                <p class="text-sm font-medium text-gray-900">
                                    {{ $record->record_date?->locale('id')->isoFormat('D MMM YYYY') }}
                                </p>
                                <p class="text-xs text-gray-500">
                                    {{ $child->getAgeAtVisit($record->record_date) }}
                                </p>
                            </div>

                            <!-- Measurements -->
                            <div class="flex flex-wrap gap-3 flex-1">
                                <span class="inline-flex items-center gap-1 text-xs text-blue-700 bg-blue-50 px-2 py-1 rounded font-medium">
                                    ⚖️ {{ number_format($record->weight, 1) }} kg
                                </span>
                                <span class="inline-flex items-center gap-1 text-xs text-green-700 bg-green-50 px-2 py-1 rounded font-medium">
                                    📏 {{ number_format($record->height, 1) }} cm
                                </span>
                                @if($record->head_circumference)
                                    <span class="inline-flex items-center gap-1 text-xs text-purple-700 bg-purple-50 px-2 py-1 rounded font-medium">
                                        🧠 {{ number_format($record->head_circumference, 1) }} cm
                                    </span>
                                @endif

                                <!-- Z-Score Status Badges -->
                                @if($record->status_bb_u)
                                    <span class="inline-flex items-center px-2 py-0.5 text-xs font-semibold rounded-full {{ $record->is_underweight ? 'bg-red-100 text-red-800' : ($record->zscore_bb_u < -1 ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800') }}">
                                        {{ $record->status_bb_u }}
                                    </span>
                                @endif
                                @if($record->status_tb_u)
                                    <span class="inline-flex items-center px-2 py-0.5 text-xs font-semibold rounded-full {{ $record->is_stunting ? 'bg-red-100 text-red-800' : ($record->zscore_tb_u < -1 ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800') }}">
                                        {{ $record->status_tb_u }}
                                    </span>
                                @endif

                                <!-- Supplement Icons -->
                                @if($record->vitamin_a)
                                    <span class="inline-flex items-center gap-0.5 text-xs text-orange-600 bg-orange-50 px-1.5 py-0.5 rounded" title="Vitamin A: {{ $record->vitamin_a }}">
                                        💊
                                    </span>
                                @endif
                                @if($record->deworming_medicine)
                                    <span class="inline-flex items-center gap-0.5 text-xs text-green-600 bg-green-50 px-1.5 py-0.5 rounded" title="Obat Cacing Diberikan">
                                        🪱
                                    </span>
                                @endif
                                @if($record->pmt_given)
                                    <span class="inline-flex items-center gap-0.5 text-xs text-blue-600 bg-blue-50 px-1.5 py-0.5 rounded" title="PMT Diberikan">
                                        🍲
                                    </span>
                                @endif
                            </div>

                            <!-- Expand Toggle -->
                            <button class="text-gray-400 hover:text-gray-600 transition-transform flex-shrink-0"
                                    :class="{ 'rotate-180': expanded }">
                                <x-heroicon-o-chevron-down class="w-5 h-5" />
                            </button>
                        </div>

                        <!-- Expanded Detail -->
                        <div x-show="expanded" x-collapse x-cloak class="px-6 pb-4 border-t border-gray-100 pt-3">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                                <!-- Z-Scores -->
                                <div>
                                    <p class="text-xs font-semibold text-gray-500 uppercase mb-2">Z-Score Detail</p>
                                    <div class="space-y-2 text-xs">
                                        @if($record->zscore_bb_u !== null)
                                            <div class="flex justify-between items-center">
                                                <span class="text-gray-600">BB/U (Berat/Umur):</span>
                                                <span class="font-mono font-bold {{ $record->is_underweight ? 'text-red-600' : 'text-green-600' }}">
                                                    {{ number_format($record->zscore_bb_u, 2) }} SD
                                                </span>
                                            </div>
                                        @endif
                                        @if($record->zscore_tb_u !== null)
                                            <div class="flex justify-between items-center">
                                                <span class="text-gray-600">TB/U (Tinggi/Umur):</span>
                                                <span class="font-mono font-bold {{ $record->is_stunting ? 'text-red-600' : 'text-green-600' }}">
                                                    {{ number_format($record->zscore_tb_u, 2) }} SD
                                                </span>
                                            </div>
                                        @endif
                                        @if($record->zscore_bb_tb !== null)
                                            <div class="flex justify-between items-center">
                                                <span class="text-gray-600">BB/TB (Berat/Tinggi):</span>
                                                <span class="font-mono font-bold {{ $record->is_wasting ? 'text-red-600' : 'text-green-600' }}">
                                                    {{ number_format($record->zscore_bb_tb, 2) }} SD
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <!-- Supplements & Interventions -->
                                <div>
                                    <p class="text-xs font-semibold text-gray-500 uppercase mb-2">Suplemen & Intervensi</p>
                                    <div class="space-y-1.5 text-xs">
                                        @if($record->vitamin_a)
                                            <p>
                                                <span class="text-gray-500">Vitamin A:</span>
                                                <span class="font-medium text-orange-700">{{ $record->vitamin_a }}</span>
                                            </p>
                                        @endif
                                        <p>
                                            <span class="text-gray-500">Obat Cacing:</span>
                                            <span class="font-medium {{ $record->deworming_medicine ? 'text-green-600' : 'text-gray-400' }}">
                                                {{ $record->deworming_medicine ? '✅ Ya' : '❌ Tidak' }}
                                            </span>
                                        </p>
                                        <p>
                                            <span class="text-gray-500">PMT:</span>
                                            <span class="font-medium {{ $record->pmt_given ? 'text-green-600' : 'text-gray-400' }}">
                                                {{ $record->pmt_given ? '✅ Ya' : '❌ Tidak' }}
                                            </span>
                                        </p>
                                        @if($record->measurement_method)
                                            <p>
                                                <span class="text-gray-500">Metode Ukur:</span>
                                                <span class="font-medium text-gray-700">{{ $record->measurement_method }}</span>
                                            </p>
                                        @endif
                                    </div>
                                </div>

                                <!-- Meta Info -->
                                <div>
                                    <p class="text-xs font-semibold text-gray-500 uppercase mb-2">Informasi Lain</p>
                                    <div class="space-y-1.5 text-xs">
                                        @if($record->age_in_months)
                                            <p>
                                                <span class="text-gray-500">Usia:</span>
                                                <span class="font-medium text-gray-700">{{ $record->age_in_months }} bulan</span>
                                            </p>
                                        @endif
                                        @if($record->midwife_name)
                                            <p>
                                                <span class="text-gray-500">Bidan:</span>
                                                <span class="font-medium text-gray-700">{{ $record->midwife_name }}</span>
                                            </p>
                                        @endif
                                        @if($record->notes)
                                            <div class="mt-2">
                                                <span class="text-gray-500">📝 Catatan:</span>
                                                <p class="font-medium text-gray-700 mt-0.5 bg-gray-50 rounded p-2 border border-gray-200">{{ $record->notes }}</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="p-8 text-center text-gray-500">
                <x-heroicon-o-chart-bar class="w-12 h-12 mx-auto mb-3 text-gray-300" />
                <p class="font-medium">Belum ada riwayat pertumbuhan</p>
                <p class="text-sm mt-1">Data akan muncul setelah pencatatan pertumbuhan</p>
                <a href="{{ route('children.growth', $child->id) }}"
                   class="inline-flex items-center gap-2 mt-4 text-sm text-purple-600 hover:text-purple-800 font-medium">
                    <x-heroicon-o-plus class="w-4 h-4" />
                    Catat Pertumbuhan
                </a>
            </div>
        @endif
    </div>
</div>
