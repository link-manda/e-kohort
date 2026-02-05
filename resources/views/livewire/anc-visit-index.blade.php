<div class="space-y-6">
    <!-- Breadcrumb -->
    <x-breadcrumb :items="[['label' => 'Kunjungan ANC']]" />

    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
                <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
                    </path>
                </svg>
                Semua Kunjungan ANC
            </h1>
            <p class="text-sm text-gray-500 mt-1">Daftar lengkap seluruh kunjungan antenatal care</p>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
        <!-- Total -->
        <div wire:click="$set('riskFilter', 'all')"
            class="bg-white rounded-xl shadow-sm border-2 {{ $riskFilter === 'all' ? 'border-blue-500' : 'border-gray-200' }} p-6 cursor-pointer hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total Kunjungan</p>
                    <p class="text-3xl font-bold text-blue-600">{{ $stats['total'] }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path>
                        <path fill-rule="evenodd"
                            d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm9.707 5.707a1 1 0 00-1.414-1.414L9 12.586l-1.293-1.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Ekstrem -->
        <div wire:click="$set('riskFilter', 'ekstrem')"
            class="bg-white rounded-xl shadow-sm border-2 {{ $riskFilter === 'ekstrem' ? 'border-red-500' : 'border-gray-200' }} p-6 cursor-pointer hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Risiko Ekstrem</p>
                    <p class="text-3xl font-bold text-red-600">{{ $stats['ekstrem'] }}</p>
                </div>
                <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                            clip-rule="evenodd"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Tinggi -->
        <div wire:click="$set('riskFilter', 'tinggi')"
            class="bg-white rounded-xl shadow-sm border-2 {{ $riskFilter === 'tinggi' ? 'border-orange-500' : 'border-gray-200' }} p-6 cursor-pointer hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Risiko Tinggi</p>
                    <p class="text-3xl font-bold text-orange-600">{{ $stats['tinggi'] }}</p>
                </div>
                <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-orange-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                            clip-rule="evenodd"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Rendah -->
        <div wire:click="$set('riskFilter', 'rendah')"
            class="bg-white rounded-xl shadow-sm border-2 {{ $riskFilter === 'rendah' ? 'border-green-500' : 'border-gray-200' }} p-6 cursor-pointer hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Risiko Rendah</p>
                    <p class="text-3xl font-bold text-green-600">{{ $stats['rendah'] }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Search -->
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Cari Pasien atau Catatan</label>
                <input type="text" wire:model.live.debounce.300ms="search"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
                    placeholder="Nama pasien, NIK, atau diagnosis...">
            </div>

            <!-- Visit Code -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Kode Kunjungan</label>
                <select wire:model.live="visitCodeFilter"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-blue-500">
                    <option value="all">Semua ({{ $stats['total'] }})</option>
                    <option value="k1">K1 ({{ $stats['k1'] }})</option>
                    <option value="k2">K2 ({{ $stats['k2'] }})</option>
                    <option value="k3">K3 ({{ $stats['k3'] }})</option>
                    <option value="k4">K4 ({{ $stats['k4'] }})</option>
                    <option value="k5">K5 ({{ $stats['k5'] }})</option>
                    <option value="k6">K6 ({{ $stats['k6'] }})</option>
                </select>
            </div>

            <!-- Per Page -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tampilkan</label>
                <select wire:model.live="perPage"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-blue-500">
                    <option value="15">15 per halaman</option>
                    <option value="25">25 per halaman</option>
                    <option value="50">50 per halaman</option>
                    <option value="100">100 per halaman</option>
                </select>
            </div>
        </div>

        @if ($riskFilter !== 'all' || $visitCodeFilter !== 'all' || $search)
            <div class="mt-3 flex items-center justify-between">
                <p class="text-sm text-gray-600">
                    Filter aktif:
                    @if ($riskFilter !== 'all')
                        <span class="font-medium">Risiko {{ ucfirst($riskFilter) }}</span>
                    @endif
                    @if ($visitCodeFilter !== 'all')
                        <span class="font-medium">{{ strtoupper($visitCodeFilter) }}</span>
                    @endif
                    @if ($search)
                        <span class="font-medium">"{{ $search }}"</span>
                    @endif
                </p>
                <button wire:click="clearFilters" class="text-sm text-blue-600 hover:text-blue-700 font-medium">
                    Reset Filter
                </button>
            </div>
        @endif
    </div>

    <!-- Visits Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        @if ($visits->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-3 text-left">
                                <button wire:click="sortBy('visit_code')"
                                    class="flex items-center gap-1 text-xs font-semibold text-gray-700 uppercase hover:text-blue-600">
                                    Kode
                                    @if ($sortField === 'visit_code')
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            @if ($sortDirection === 'asc')
                                                <path fill-rule="evenodd"
                                                    d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z"
                                                    clip-rule="evenodd"></path>
                                            @else
                                                <path fill-rule="evenodd"
                                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                    clip-rule="evenodd"></path>
                                            @endif
                                        </svg>
                                    @endif
                                </button>
                            </th>
                            <th class="px-6 py-3 text-left">
                                <button wire:click="sortBy('visit_date')"
                                    class="flex items-center gap-1 text-xs font-semibold text-gray-700 uppercase hover:text-blue-600">
                                    Tanggal
                                    @if ($sortField === 'visit_date')
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            @if ($sortDirection === 'asc')
                                                <path fill-rule="evenodd"
                                                    d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z"
                                                    clip-rule="evenodd"></path>
                                            @else
                                                <path fill-rule="evenodd"
                                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                    clip-rule="evenodd"></path>
                                            @endif
                                        </svg>
                                    @endif
                                </button>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Pasien</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">UK</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Indikator
                            </th>
                            <th class="px-6 py-3 text-left">
                                <button wire:click="sortBy('risk_category')"
                                    class="flex items-center gap-1 text-xs font-semibold text-gray-700 uppercase hover:text-blue-600">
                                    Risiko
                                    @if ($sortField === 'risk_category')
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            @if ($sortDirection === 'asc')
                                                <path fill-rule="evenodd"
                                                    d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z"
                                                    clip-rule="evenodd"></path>
                                            @else
                                                <path fill-rule="evenodd"
                                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                    clip-rule="evenodd"></path>
                                            @endif
                                        </svg>
                                    @endif
                                </button>
                            </th>
                            <th class="px-6 py-3 text-center text-xs font-semibold text-gray-700 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach ($visits as $visit)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <!-- Visit Code -->
                                <td class="px-6 py-4">
                                    <span
                                        class="inline-flex items-center px-3 py-1 text-sm font-bold rounded-full
                                        {{ in_array($visit->visit_code, ['K1', 'K2']) ? 'bg-blue-100 text-blue-800' : '' }}
                                        {{ in_array($visit->visit_code, ['K3', 'K4']) ? 'bg-green-100 text-green-800' : '' }}
                                        {{ in_array($visit->visit_code, ['K5', 'K6']) ? 'bg-purple-100 text-purple-800' : '' }}">
                                        {{ $visit->visit_code }}
                                    </span>
                                </td>

                                <!-- Date -->
                                <td class="px-6 py-4">
                                    <p class="text-sm font-medium text-gray-900">
                                        {{ $visit->visit_date->locale('id')->isoFormat('D MMM YYYY') }}</p>
                                    <p class="text-xs text-gray-500">{{ $visit->visit_date->diffForHumans() }}</p>
                                </td>

                                <!-- Patient -->
                                <td class="px-6 py-4">
                                    @if ($visit->pregnancy && $visit->pregnancy->patient)
                                        <p class="text-sm font-semibold text-gray-900">
                                            {{ $visit->pregnancy->patient->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $visit->pregnancy->gravida }}</p>
                                    @else
                                        <p class="text-sm text-gray-500 italic">Data tidak lengkap</p>
                                    @endif
                                </td>

                                <!-- UK -->
                                <td class="px-6 py-4">
                                    <span class="text-sm font-semibold text-gray-900">{{ $visit->gestational_age }}
                                        mg</span>
                                </td>

                                <!-- Indicators -->
                                <td class="px-6 py-4">
                                    <div class="space-y-1 text-xs">
                                        <p class="flex items-center gap-1">
                                            <span class="font-medium text-gray-600">MAP:</span>
                                            <span
                                                class="font-bold {{ $visit->map_score > 100 ? 'text-red-600' : ($visit->map_score > 90 ? 'text-orange-600' : 'text-green-600') }}">
                                                {{ number_format($visit->map_score, 1) }}
                                            </span>
                                        </p>
                                        @if ($visit->hb)
                                            <p class="flex items-center gap-1">
                                                <span class="font-medium text-gray-600">Hb:</span>
                                                <span
                                                    class="font-bold {{ $visit->hb < 11 ? 'text-red-600' : 'text-green-600' }}">
                                                    {{ number_format($visit->hb, 1) }}
                                                </span>
                                            </p>
                                        @endif
                                    </div>
                                </td>

                                <!-- Risk -->
                                <td class="px-6 py-4">
                                    <span
                                        class="inline-flex items-center px-3 py-1 text-sm font-bold rounded-full
                                        {{ $visit->risk_category === 'Ekstrem' ? 'bg-red-100 text-red-800' : '' }}
                                        {{ $visit->risk_category === 'Tinggi' ? 'bg-orange-100 text-orange-800' : '' }}
                                        {{ $visit->risk_category === 'Rendah' ? 'bg-green-100 text-green-800' : '' }}">
                                        {{ $visit->risk_category }}
                                    </span>
                                </td>

                                <!-- Actions -->
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('anc-visits.show', $visit->id) }}"
                                            class="p-1.5 text-blue-600 hover:text-blue-700 hover:bg-blue-50 rounded transition-colors"
                                            title="Lihat Detail">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                </path>
                                            </svg>
                                        </a>
                                        @if ($visit->pregnancy)
                                            <a href="{{ route('anc-visits.history', $visit->pregnancy_id) }}"
                                                class="p-1.5 text-purple-600 hover:text-purple-700 hover:bg-purple-50 rounded transition-colors"
                                                title="Riwayat Kunjungan (Delete/Edit)">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                            </a>
                                        @endif
                                        @if ($visit->pregnancy && $visit->pregnancy->patient)
                                            <a href="{{ route('patients.show', $visit->pregnancy->patient->id) }}"
                                                class="p-1.5 text-green-600 hover:text-green-700 hover:bg-green-50 rounded transition-colors"
                                                title="Lihat Pasien">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                                    </path>
                                                </svg>
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $visits->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-12">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                    </path>
                </svg>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Tidak ada kunjungan ANC</h3>
                <p class="text-gray-600 mb-4">
                    @if ($search || $riskFilter !== 'all' || $visitCodeFilter !== 'all')
                        Tidak ada kunjungan yang sesuai dengan filter. Coba ubah filter pencarian.
                    @else
                        Belum ada kunjungan ANC yang tercatat dalam sistem.
                    @endif
                </p>
                @if ($search || $riskFilter !== 'all' || $visitCodeFilter !== 'all')
                    <button wire:click="clearFilters"
                        class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-colors">
                        Reset Filter
                    </button>
                @endif
            </div>
        @endif
    </div>
</div>
