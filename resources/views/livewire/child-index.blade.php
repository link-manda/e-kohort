<div>
    <!-- Breadcrumb -->
    <x-breadcrumb :items="[['label' => 'Data Pasien Anak']]" />

    <!-- Header & Search Section -->
    <div class="mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">👶 Data Pasien Anak</h2>
                <p class="text-sm text-gray-500 mt-1">Kelola data bayi dan anak untuk pencatatan imunisasi</p>
            </div>
            <a href="{{ route('imunisasi.register') }}"
                class="inline-flex items-center justify-center gap-2 px-4 py-3 md:py-2 min-h-[44px] bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-colors shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                <span class="hidden sm:inline">Tambah Bayi Baru</span>
                <span class="sm:hidden">Tambah</span>
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-6">
        <button wire:click="$set('immunizationFilter', 'all')"
            class="bg-white rounded-xl shadow-sm border-2 transition-all text-left p-6 {{ $immunizationFilter === 'all' ? 'border-blue-500 bg-blue-50' : 'border-gray-200 hover:border-blue-300' }}">
            <p class="text-sm font-medium text-gray-600">Total Anak</p>
            <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['total'] }}</p>
        </button>

        <button wire:click="$set('immunizationFilter', 'complete')"
            class="bg-white rounded-xl shadow-sm border-2 transition-all text-left p-6 {{ $immunizationFilter === 'complete' ? 'border-green-500 bg-green-50' : 'border-gray-200 hover:border-green-300' }}">
            <p class="text-sm font-medium text-gray-600">Imunisasi Lengkap</p>
            <p class="text-3xl font-bold text-green-600 mt-2">{{ $stats['complete'] }}</p>
        </button>

        <button wire:click="$set('immunizationFilter', 'partial')"
            class="bg-white rounded-xl shadow-sm border-2 transition-all text-left p-6 {{ $immunizationFilter === 'partial' ? 'border-yellow-500 bg-yellow-50' : 'border-gray-200 hover:border-yellow-300' }}">
            <p class="text-sm font-medium text-gray-600">Imunisasi Sebagian</p>
            <p class="text-3xl font-bold text-yellow-600 mt-2">{{ $stats['partial'] }}</p>
        </button>

        <button wire:click="$set('immunizationFilter', 'none')"
            class="bg-white rounded-xl shadow-sm border-2 transition-all text-left p-6 {{ $immunizationFilter === 'none' ? 'border-gray-500 bg-gray-50' : 'border-gray-200 hover:border-gray-300' }}">
            <p class="text-sm font-medium text-gray-600">Belum Imunisasi</p>
            <p class="text-3xl font-bold text-gray-600 mt-2">{{ $stats['none'] }}</p>
        </button>
    </div>

    <!-- Search & Filters -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
            <!-- Search Input -->
            <div class="md:col-span-5">
                <div class="relative">
                    <input type="text" wire:model.live.debounce.300ms="search"
                        placeholder="Cari Nama Anak, No RM, atau Nama Ibu..."
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

            <!-- Immunization Filter -->
            <div class="md:col-span-3">
                <select wire:model.live="immunizationFilter"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="all">Semua Status Imunisasi</option>
                    <option value="complete">Imunisasi Lengkap</option>
                    <option value="partial">Imunisasi Sebagian</option>
                    <option value="none">Belum Imunisasi</option>
                </select>
            </div>

            <!-- Age Filter -->
            <div class="md:col-span-2">
                <select wire:model.live="ageFilter"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="all">Semua Usia</option>
                    <option value="under1">
                        < 1 Tahun</option>
                    <option value="1-2">1-2 Tahun</option>
                    <option value="over2">> 2 Tahun</option>
                </select>
            </div>

            <!-- Clear Filters -->
            <div class="md:col-span-2">
                <button wire:click="clearFilters"
                    class="w-full px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg transition-colors">
                    Reset Filter
                </button>
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
            <span class="text-sm text-blue-700 font-medium">Memuat data anak...</span>
        </div>
    </div>

    <!-- Patient Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Anak</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">JK
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Tanggal Lahir</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Usia</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Nama Ibu</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Status Imunisasi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <!-- Loading State -->
                    <tr wire:loading.delay>
                        <td colspan="6" class="px-6 py-8 bg-gray-50">
                            <div class="flex items-center justify-center space-x-3">
                                <svg class="animate-spin h-5 w-5 text-blue-600" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                                <span class="text-sm text-gray-600">Memuat data anak...</span>
                            </div>
                        </td>
                    </tr>

                    <!-- Data Rows -->
                    @forelse($children as $child)
                        <tr class="hover:bg-blue-50 transition-colors cursor-pointer" wire:loading.remove
                            onclick="window.location='{{ route('imunisasi.kunjungan', $child->id) }}'">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div
                                        class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white font-bold flex-shrink-0">
                                        {{ strtoupper(substr($child->name, 0, 2)) }}
                                    </div>
                                    <div class="ml-4">
                                        <div class="font-semibold text-gray-600 hover:text-gray-700">
                                            {{ $child->name }}
                                        </div>
                                        <div class="text-sm text-gray-500">No. RM: {{ $child->no_rm }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if ($child->gender === 'L')
                                    <span
                                        class="inline-flex items-center text-sm text-blue-600 font-semibold">Laki-laki</span>
                                @else
                                    <span
                                        class="inline-flex items-center text-sm text-pink-600 font-semibold">Perempuan</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $child->dob?->format('d M Y') }}
                                </div>
                                <div class="text-xs text-gray-500">{{ $child->pob }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $child->getDetailedAge() }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $child->patient?->name }}</div>
                                <div class="text-xs text-gray-500">{{ $child->patient?->phone }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $immunizationCount = $child->childVisits->flatMap->immunizationActions
                                        ->pluck('vaccine_type')
                                        ->unique()
                                        ->count();
                                    $isComplete = $immunizationCount >= 10;
                                    $isPartial = $immunizationCount > 0 && $immunizationCount < 10;
                                    $isNone = $immunizationCount === 0;
                                @endphp
                                @if ($isComplete)
                                    <span
                                        class="inline-flex items-center gap-1 px-2 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                        Lengkap ({{ $immunizationCount }})
                                    </span>
                                @elseif ($isPartial)
                                    <span
                                        class="inline-flex items-center gap-1 px-2 py-1 bg-yellow-100 text-yellow-800 text-xs font-semibold rounded-full">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                        Sebagian ({{ $immunizationCount }})
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center px-2 py-1 bg-gray-100 text-gray-600 text-xs font-semibold rounded-full">
                                        Belum Imunisasi
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                                    </path>
                                </svg>
                                <p class="text-gray-500 font-medium mb-2">Tidak ada data anak ditemukan</p>
                                @if ($search || $immunizationFilter !== 'all' || $ageFilter !== 'all')
                                    <button wire:click="clearFilters"
                                        class="text-sm text-blue-600 hover:text-blue-700 font-medium">
                                        Reset filter untuk melihat semua anak
                                    </button>
                                @else
                                    <a href="{{ route('imunisasi.register') }}"
                                        class="text-sm text-blue-600 hover:text-blue-700 font-medium">
                                        Tambah anak pertama →
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if ($children->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $children->links() }}
            </div>
        @endif
    </div>

    <!-- Summary -->
    <div class="mt-4 text-sm text-gray-500 text-center">
        Menampilkan {{ $children->firstItem() ?? 0 }} - {{ $children->lastItem() ?? 0 }} dari
        {{ $children->total() }} anak
    </div>
</div>
