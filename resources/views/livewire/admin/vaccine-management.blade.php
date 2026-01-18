<div>
    <!-- Breadcrumb -->
    <x-breadcrumb :items="[['label' => 'Admin', 'url' => '#'], ['label' => 'Master Vaksin']]" />

    <!-- Header & Search Section -->
    <div class="mb-6 px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">💉 Master Vaksin</h2>
                <p class="text-sm text-gray-500 mt-1">Kelola daftar vaksin program imunisasi</p>
            </div>
            <button wire:click="create"
                class="inline-flex items-center justify-center gap-2 px-4 py-3 md:py-2 min-h-[44px] bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-colors shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                <span class="hidden sm:inline">Tambah Vaksin</span>
                <span class="sm:hidden">Tambah</span>
            </button>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 md:gap-4 mb-6 px-4 sm:px-6 lg:px-8">
        <button wire:click="$set('statusFilter', 'all')"
            class="p-3 md:p-4 rounded-lg border-2 transition-all text-left min-h-[80px] {{ $statusFilter === 'all' ? 'border-blue-500 bg-blue-50' : 'border-gray-200 hover:border-blue-300' }}">
            <p class="text-xs md:text-sm font-medium text-gray-600">Total Vaksin</p>
            <p class="text-xl md:text-2xl font-bold text-gray-900 mt-1">{{ $stats['total'] }}</p>
        </button>

        <button wire:click="$set('statusFilter', 'active')"
            class="p-4 rounded-lg border-2 transition-all text-left {{ $statusFilter === 'active' ? 'border-green-500 bg-green-50' : 'border-gray-200 hover:border-green-300' }}">
            <p class="text-sm font-medium text-gray-600">Vaksin Aktif</p>
            <p class="text-2xl font-bold text-green-600 mt-1">{{ $stats['active'] }}</p>
        </button>

        <button wire:click="$set('statusFilter', 'inactive')"
            class="p-4 rounded-lg border-2 transition-all text-left {{ $statusFilter === 'inactive' ? 'border-yellow-500 bg-yellow-50' : 'border-gray-200 hover:border-yellow-300' }}">
            <p class="text-sm font-medium text-gray-600">Vaksin Nonaktif</p>
            <p class="text-2xl font-bold text-yellow-600 mt-1">{{ $stats['inactive'] }}</p>
        </button>

        <button wire:click="$set('statusFilter', 'deleted')"
            class="p-4 rounded-lg border-2 transition-all text-left {{ $statusFilter === 'deleted' ? 'border-red-500 bg-red-50' : 'border-gray-200 hover:border-red-300' }}">
            <p class="text-sm font-medium text-gray-600">Vaksin Terhapus</p>
            <p class="text-2xl font-bold text-red-600 mt-1">{{ $stats['deleted'] }}</p>
        </button>
    </div>

    <!-- Search & Filters -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 mb-6 mx-4 sm:mx-6 lg:mx-8">
        <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
            <!-- Search Input -->
            <div class="md:col-span-6">
                <div class="relative">
                    <input type="text" wire:model.live.debounce.300ms="search"
                        placeholder="Cari kode, nama, atau deskripsi vaksin..."
                        class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <svg class="absolute left-3 top-2.5 w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    @if ($search)
                        <button wire:click="$set('search', '')"
                            class="absolute right-3 top-2.5 text-gray-400 hover:text-gray-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    @endif
                </div>
            </div>

            <!-- Status Filter -->
            <div class="md:col-span-3">
                <select wire:model.live="statusFilter"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="all">Semua Status</option>
                    <option value="active">Aktif</option>
                    <option value="inactive">Nonaktif</option>
                    <option value="deleted">Terhapus</option>
                </select>
            </div>

            <!-- Clear Filters -->
            <div class="md:col-span-3">
                <button wire:click="clearFilters"
                    class="w-full px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg transition-colors">
                    Reset Filter
                </button>
            </div>
        </div>
    </div>

    <!-- Loading Indicator -->
    <div wire:loading class="mb-4 px-4 sm:px-6 lg:px-8">
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 flex items-center gap-3">
            <svg class="animate-spin h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                </circle>
                <path class="opacity-75" fill="currentColor"
                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                </path>
            </svg>
            <span class="text-sm text-blue-700 font-medium">Memuat data vaksin...</span>
        </div>
    </div>

    <!-- Vaccine Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mx-4 sm:mx-6 lg:mx-8">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Kode</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Vaksin</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Usia (bulan)</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Status</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <!-- Loading State -->
                    <tr wire:loading.delay>
                        <td colspan="5" class="px-6 py-8 bg-gray-50">
                            <div class="flex items-center justify-center space-x-3">
                                <svg class="animate-spin h-5 w-5 text-blue-600" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                                <span class="text-sm text-gray-600">Memuat data vaksin...</span>
                            </div>
                        </td>
                    </tr>

                    <!-- Data Rows -->
                    @forelse($vaccines as $vac)
                        <tr class="hover:bg-blue-50 transition-colors" wire:loading.remove>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="font-mono text-sm font-semibold text-gray-900">{{ $vac->code }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-semibold text-gray-900">{{ $vac->name }}</div>
                                @if ($vac->description)
                                    <div class="text-sm text-gray-500 mt-1">{{ Str::limit($vac->description, 60) }}
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $vac->min_age_months }} -
                                    {{ $vac->max_age_months }} bulan</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if ($vac->deleted_at)
                                    <span
                                        class="inline-flex items-center gap-1 px-2 py-1 bg-red-100 text-red-800 text-xs font-semibold rounded-full">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                        Terhapus
                                    </span>
                                @else
                                    @if ($vac->is_active)
                                        <span
                                            class="inline-flex items-center gap-1 px-2 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                    clip-rule="evenodd"></path>
                                            </svg>
                                            Aktif
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center gap-1 px-2 py-1 bg-yellow-100 text-yellow-800 text-xs font-semibold rounded-full">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                                    clip-rule="evenodd"></path>
                                            </svg>
                                            Nonaktif
                                        </span>
                                    @endif
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-2">
                                    @if (!$vac->deleted_at)
                                        <button wire:click="edit({{ $vac->id }})"
                                            class="inline-flex items-center gap-1 px-3 py-1 bg-yellow-500 hover:bg-yellow-600 text-white text-sm font-medium rounded-md transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                </path>
                                            </svg>
                                            Edit
                                        </button>
                                        <button wire:click="toggleActive({{ $vac->id }})"
                                            class="inline-flex items-center gap-1 px-3 py-1 bg-indigo-500 hover:bg-indigo-600 text-white text-sm font-medium rounded-md transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                                </path>
                                            </svg>
                                            Toggle
                                        </button>
                                        <button wire:click="confirmDelete({{ $vac->id }})"
                                            class="inline-flex items-center gap-1 px-3 py-1 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-md transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                </path>
                                            </svg>
                                            Hapus
                                        </button>
                                    @else
                                        <button wire:click="restore({{ $vac->id }})"
                                            class="inline-flex items-center gap-1 px-3 py-1 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-md transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                                </path>
                                            </svg>
                                            Restore
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg>
                                <p class="text-gray-500 font-medium mb-2">Tidak ada data vaksin ditemukan</p>
                                @if ($search || $statusFilter !== 'all')
                                    <button wire:click="clearFilters"
                                        class="text-sm text-blue-600 hover:text-blue-700 font-medium">
                                        Reset filter untuk melihat semua vaksin
                                    </button>
                                @else
                                    <button wire:click="create"
                                        class="text-sm text-blue-600 hover:text-blue-700 font-medium">
                                        Tambah vaksin pertama →
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if ($vaccines->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $vaccines->links() }}
            </div>
        @endif
    </div>

    <!-- Summary -->
    <div class="mt-4 text-sm text-gray-500 text-center px-4 sm:px-6 lg:px-8">
        Menampilkan {{ $vaccines->firstItem() ?? 0 }} - {{ $vaccines->lastItem() ?? 0 }} dari
        {{ $vaccines->total() }} vaksin
    </div>

    <!-- Modal -->
    @if ($showModal)
        <div class="fixed inset-0 bg-black/40 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg p-6 w-full max-w-2xl">
                <h3 class="text-lg font-bold mb-4">{{ $editing ? 'Edit Vaksin' : 'Tambah Vaksin' }}</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div>
                        <label class="text-sm font-semibold">Kode</label>
                        <input wire:model.defer="code" class="w-full px-3 py-2 border rounded-lg">
                        @error('code')
                            <p class="text-red-500 text-xs">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="text-sm font-semibold">Nama</label>
                        <input wire:model.defer="name" class="w-full px-3 py-2 border rounded-lg">
                        @error('name')
                            <p class="text-red-500 text-xs">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="md:col-span-2">
                        <label class="text-sm font-semibold">Deskripsi</label>
                        <textarea wire:model.defer="description" class="w-full px-3 py-2 border rounded-lg"></textarea>
                    </div>
                    <div>
                        <label class="text-sm font-semibold">Min Age (bulan)</label>
                        <input type="number" wire:model.defer="min_age_months"
                            class="w-full px-3 py-2 border rounded-lg">
                    </div>
                    <div>
                        <label class="text-sm font-semibold">Max Age (bulan)</label>
                        <input type="number" wire:model.defer="max_age_months"
                            class="w-full px-3 py-2 border rounded-lg">
                    </div>
                    <div>
                        <label class="text-sm font-semibold">Urutan</label>
                        <input type="number" wire:model.defer="sort_order"
                            class="w-full px-3 py-2 border rounded-lg">
                    </div>
                    <div>
                        <label class="flex items-center gap-2"><input type="checkbox" wire:model.defer="is_active">
                            Aktif</label>
                    </div>
                </div>

                <div class="mt-4 flex justify-end gap-2">
                    <button wire:click="$set('showModal', false)" class="px-4 py-2 bg-gray-200 rounded">Batal</button>
                    <button wire:click="save" class="px-4 py-2 bg-blue-600 text-white rounded">Simpan</button>
                </div>
            </div>
        </div>
    @endif
</div>
