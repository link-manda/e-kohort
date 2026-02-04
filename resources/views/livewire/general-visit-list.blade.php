<div>
    <!-- Breadcrumb -->
    <x-breadcrumb :items="[['label' => 'Poli Umum']]" />

    <!-- Header -->
    <div class="mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Daftar Kunjungan Poli Umum</h2>
                <p class="text-sm text-gray-500 mt-1">Riwayat kunjungan pasien poli umum</p>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 md:gap-4 mb-6">
        <div class="p-3 md:p-4 rounded-lg bg-blue-50 border-2 border-blue-200">
            <p class="text-xs md:text-sm font-medium text-gray-600">Total Kunjungan</p>
            <p class="text-xl md:text-2xl font-bold text-blue-600 mt-1">{{ $stats['total'] }}</p>
        </div>

        <div class="p-3 md:p-4 rounded-lg bg-green-50 border-2 border-green-200">
            <p class="text-xs md:text-sm font-medium text-gray-600">Hari Ini</p>
            <p class="text-xl md:text-2xl font-bold text-green-600 mt-1">{{ $stats['today'] }}</p>
        </div>

        <button wire:click="$set('statusFilter', 'Rujuk')"
            class="p-3 md:p-4 rounded-lg border-2 transition-all text-left {{ $statusFilter === 'Rujuk' ? 'border-orange-500 bg-orange-50' : 'border-gray-200 hover:border-orange-300' }}">
            <p class="text-xs md:text-sm font-medium text-gray-600">Dirujuk</p>
            <p class="text-xl md:text-2xl font-bold text-orange-600 mt-1">{{ $stats['status']['Rujuk'] }}</p>
        </button>

        <button wire:click="$set('statusFilter', 'Rawat Inap')"
            class="p-3 md:p-4 rounded-lg border-2 transition-all text-left {{ $statusFilter === 'Rawat Inap' ? 'border-red-500 bg-red-50' : 'border-gray-200 hover:border-red-300' }}">
            <p class="text-xs md:text-sm font-medium text-gray-600">Rawat Inap</p>
            <p class="text-xl md:text-2xl font-bold text-red-600 mt-1">{{ $stats['status']['Rawat Inap'] }}</p>
        </button>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
            <!-- Search -->
            <div class="md:col-span-4">
                <div class="relative">
                    <input type="text" wire:model.live.debounce.300ms="search"
                        placeholder="Cari nama atau NIK pasien..."
                        class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <x-heroicon-o-magnifying-glass class="absolute left-3 top-2.5 w-5 h-5 text-gray-400" />
                </div>
            </div>

            <!-- Date From -->
            <div class="md:col-span-2">
                <input type="date" wire:model.live="dateFrom"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Date To -->
            <div class="md:col-span-2">
                <input type="date" wire:model.live="dateTo"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Status Filter -->
            <div class="md:col-span-2">
                <select wire:model.live="statusFilter"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="all">Semua Status</option>
                    <option value="Pulang">Pulang</option>
                    <option value="Rujuk">Rujuk</option>
                    <option value="Rawat Inap">Rawat Inap</option>
                </select>
            </div>

            <!-- Payment Filter -->
            <div class="md:col-span-2">
                <select wire:model.live="paymentFilter"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="all">Semua Pembayaran</option>
                    <option value="Umum">Umum</option>
                    <option value="BPJS">BPJS</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Loading Indicator -->
    <div wire:loading class="mb-4">
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 flex items-center gap-3">
            <svg class="animate-spin h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                </circle>
                <path class="opacity-75" fill="currentColor"
                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                </path>
            </svg>
            <span class="text-sm text-blue-700 font-medium">Memuat data...</span>
        </div>
    </div>

    <!-- Visits Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Pasien</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Keluhan</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Diagnosa</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Status</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Pembayaran</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($visits as $visit)
                        <tr class="hover:bg-blue-50 transition-colors">
                            <!-- Tanggal -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $visit->visit_date->format('d/m/Y') }}
                                </div>
                                <div class="text-xs text-gray-500">
                                    {{ $visit->visit_date->format('H:i') }}
                                </div>
                            </td>

                            <!-- Pasien -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div
                                        class="w-10 h-10 rounded-full flex items-center justify-center text-white font-bold flex-shrink-0 {{ $visit->patient->gender === 'L' ? 'bg-gradient-to-br from-blue-400 to-blue-600' : 'bg-gradient-to-br from-pink-400 to-pink-600' }}">
                                        {{ strtoupper(substr($visit->patient->name, 0, 2)) }}
                                    </div>
                                    <div class="ml-4">
                                        <div class="font-semibold text-gray-900">{{ $visit->patient->name }}</div>
                                        <div class="text-xs text-gray-500">{{ $visit->patient->nik ?? 'Tanpa NIK' }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <!-- Keluhan -->
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900 max-w-xs truncate">{{ $visit->complaint }}</div>
                            </td>

                            <!-- Diagnosa -->
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900">{{ $visit->diagnosis }}</div>
                                @if ($visit->icd10_code)
                                    <div class="text-xs text-gray-500">{{ $visit->icd10_code }}</div>
                                @endif
                            </td>

                            <!-- Status -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if ($visit->status === 'Pulang')
                                    <span
                                        class="inline-flex items-center px-2.5 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">
                                        Pulang
                                    </span>
                                @elseif($visit->status === 'Rujuk')
                                    <span
                                        class="inline-flex items-center px-2.5 py-1 bg-orange-100 text-orange-800 text-xs font-semibold rounded-full">
                                        Rujuk
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center px-2.5 py-1 bg-red-100 text-red-800 text-xs font-semibold rounded-full">
                                        Rawat Inap
                                    </span>
                                @endif
                            </td>

                            <!-- Pembayaran -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if ($visit->payment_method === 'BPJS')
                                    <span
                                        class="inline-flex items-center px-2.5 py-1 bg-blue-100 text-blue-800 text-xs font-semibold rounded-full">
                                        BPJS
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center px-2.5 py-1 bg-gray-100 text-gray-800 text-xs font-semibold rounded-full">
                                        Umum
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center text-gray-500">
                                    <x-heroicon-o-clipboard-document-list class="w-12 h-12 mb-2 text-gray-300" />
                                    <p class="text-sm font-medium">Tidak ada data kunjungan</p>
                                    <p class="text-xs mt-1">Belum ada kunjungan poli umum pada periode ini</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if ($visits->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $visits->links() }}
            </div>
        @endif
    </div>
</div>
