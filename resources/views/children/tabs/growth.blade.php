@php
    $growthRecords = $child->growthRecords ?? collect();
    $latestGrowth = $growthRecords->first();
@endphp

<div class="space-y-6">
    <!-- Latest Growth Summary -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                <x-heroicon-o-chart-bar class="w-5 h-5 text-purple-600" />
                Data Pertumbuhan Terakhir
            </h3>
            <a href="{{ route('children.growth', $child->id) }}"
               class="text-sm text-purple-600 hover:text-purple-800 font-medium">
                + Tambah Data
            </a>
        </div>

        @if($latestGrowth)
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="bg-blue-50 rounded-lg p-4 text-center">
                    <p class="text-xs text-gray-500 mb-1">Berat Badan</p>
                    <p class="text-2xl font-bold text-blue-600">{{ number_format($latestGrowth->weight, 1) }}</p>
                    <p class="text-xs text-gray-500">kg</p>
                </div>
                <div class="bg-green-50 rounded-lg p-4 text-center">
                    <p class="text-xs text-gray-500 mb-1">Tinggi Badan</p>
                    <p class="text-2xl font-bold text-green-600">{{ number_format($latestGrowth->height, 1) }}</p>
                    <p class="text-xs text-gray-500">cm</p>
                </div>
                @if($latestGrowth->head_circumference)
                    <div class="bg-purple-50 rounded-lg p-4 text-center">
                        <p class="text-xs text-gray-500 mb-1">Lingkar Kepala</p>
                        <p class="text-2xl font-bold text-purple-600">{{ number_format($latestGrowth->head_circumference, 1) }}</p>
                        <p class="text-xs text-gray-500">cm</p>
                    </div>
                @endif
                <div class="bg-gray-50 rounded-lg p-4 text-center">
                    <p class="text-xs text-gray-500 mb-1">Tanggal Ukur</p>
                    <p class="text-sm font-bold text-gray-700">{{ $latestGrowth->record_date?->locale('id')->isoFormat('D MMM YY') }}</p>
                    <p class="text-xs text-gray-500">{{ $child->getAgeAtVisit($latestGrowth->record_date) }}</p>
                </div>
            </div>
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
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Usia</th>
                            <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase">BB (kg)</th>
                            <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase">TB (cm)</th>
                            <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase">LK (cm)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($growthRecords->sortByDesc('record_date') as $record)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    {{ $record->record_date?->locale('id')->isoFormat('D MMM YYYY') }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    {{ $child->getAgeAtVisit($record->record_date) }}
                                </td>
                                <td class="px-6 py-4 text-center text-sm font-medium text-gray-900">
                                    {{ number_format($record->weight, 1) }}
                                </td>
                                <td class="px-6 py-4 text-center text-sm font-medium text-gray-900">
                                    {{ number_format($record->height, 1) }}
                                </td>
                                <td class="px-6 py-4 text-center text-sm text-gray-600">
                                    {{ $record->head_circumference ? number_format($record->head_circumference, 1) : '-' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
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
