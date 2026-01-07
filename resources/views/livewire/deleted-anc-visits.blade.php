<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                    </path>
                </svg>
                Kunjungan ANC yang Dihapus
            </h2>
            <p class="text-sm text-gray-500 mt-1">Kelola kunjungan yang telah dihapus - Restore atau hapus permanen</p>
        </div>
    </div>

    <!-- Flash Messages -->
    @if (session()->has('success'))
        <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded">
            <div class="flex items-center gap-3">
                <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                        clip-rule="evenodd"></path>
                </svg>
                <p class="text-sm text-green-800">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="bg-white rounded-lg shadow-sm border-2 border-red-500 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total Dihapus</p>
                    <p class="text-3xl font-bold text-red-600">{{ $stats['total'] }}</p>
                </div>
                <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                            clip-rule="evenodd"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border-2 border-orange-500 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Bulan Ini</p>
                    <p class="text-3xl font-bold text-orange-600">{{ $stats['thisMonth'] }}</p>
                </div>
                <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-orange-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                            clip-rule="evenodd"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters & Search -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Search -->
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Pencarian</label>
                <div class="relative">
                    <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <input wire:model.live.debounce.300ms="search" type="text"
                        class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Cari pasien, NIK, kode kunjungan, atau alasan...">
                </div>
            </div>

            <!-- Per Page -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Per Halaman</label>
                <select wire:model.live="perPage"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="15">15</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        @if ($visits->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-4 py-3 text-left">
                                <button wire:click="sortBy('visit_code')"
                                    class="flex items-center gap-1 text-xs font-semibold text-gray-700 uppercase tracking-wider hover:text-gray-900">
                                    Visit Code
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
                            <th class="px-4 py-3 text-left">
                                <button wire:click="sortBy('visit_date')"
                                    class="flex items-center gap-1 text-xs font-semibold text-gray-700 uppercase tracking-wider hover:text-gray-900">
                                    Tanggal Kunjungan
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
                            <th
                                class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                Pasien</th>
                            <th
                                class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                Alasan Penghapusan</th>
                            <th class="px-4 py-3 text-left">
                                <button wire:click="sortBy('deleted_at')"
                                    class="flex items-center gap-1 text-xs font-semibold text-gray-700 uppercase tracking-wider hover:text-gray-900">
                                    Dihapus Pada
                                    @if ($sortField === 'deleted_at')
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
                            <th
                                class="px-4 py-3 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach ($visits as $visit)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <!-- Visit Code -->
                                <td class="px-4 py-3">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        {{ $visit->visit_code }}
                                    </span>
                                </td>

                                <!-- Visit Date -->
                                <td class="px-4 py-3">
                                    <p class="text-sm text-gray-900">{{ $visit->visit_date->format('d M Y') }}</p>
                                    <p class="text-xs text-gray-500">{{ $visit->gestational_age }} minggu</p>
                                </td>

                                <!-- Patient -->
                                <td class="px-4 py-3">
                                    @if ($visit->pregnancy && $visit->pregnancy->patient)
                                        <p class="text-sm font-semibold text-gray-900">
                                            {{ $visit->pregnancy->patient->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $visit->pregnancy->patient->nik }}</p>
                                    @else
                                        <p class="text-sm text-gray-500 italic">Data tidak lengkap</p>
                                    @endif
                                </td>

                                <!-- Deletion Reason -->
                                <td class="px-4 py-3">
                                    <p class="text-sm text-gray-600">
                                        {{ $visit->deleted_reason ?: '-' }}
                                    </p>
                                </td>

                                <!-- Deleted At -->
                                <td class="px-4 py-3">
                                    <p class="text-sm text-gray-900">{{ $visit->deleted_at->format('d M Y') }}</p>
                                    <p class="text-xs text-gray-500">{{ $visit->deleted_at->diffForHumans() }}</p>
                                </td>

                                <!-- Actions -->
                                <td class="px-4 py-3">
                                    <div class="flex items-center justify-end gap-2">
                                        <!-- Restore Button -->
                                        <button wire:click="restore({{ $visit->id }})"
                                            wire:confirm="Yakin ingin memulihkan kunjungan {{ $visit->visit_code }}?"
                                            class="inline-flex items-center gap-1 px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white text-xs font-semibold rounded transition-colors"
                                            title="Restore kunjungan">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                                </path>
                                            </svg>
                                            Restore
                                        </button>

                                        <!-- Permanent Delete Button -->
                                        <button wire:click="forceDelete({{ $visit->id }})"
                                            wire:confirm="PERINGATAN: Ini akan menghapus data secara PERMANEN dan tidak dapat dipulihkan. Lanjutkan?"
                                            class="inline-flex items-center gap-1 px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white text-xs font-semibold rounded transition-colors"
                                            title="Hapus permanen">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                </path>
                                            </svg>
                                            Hapus Permanen
                                        </button>
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
                <h3 class="text-lg font-semibold text-gray-900 mb-2">
                    @if ($search)
                        Tidak ada hasil pencarian
                    @else
                        Tidak ada kunjungan yang dihapus
                    @endif
                </h3>
                <p class="text-gray-600">
                    @if ($search)
                        Coba ubah kata kunci pencarian Anda
                    @else
                        Semua data kunjungan dalam kondisi baik
                    @endif
                </p>
            </div>
        @endif
    </div>
</div>
