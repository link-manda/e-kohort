<div>
    <!-- Breadcrumb -->
    <x-breadcrumb :items="[['label' => 'Data Pasien']]" />

    <!-- Header & Search Section -->
    <div class="mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Data Pasien</h2>
                <p class="text-sm text-gray-500 mt-1">Kelola data pasien dan kehamilan</p>
            </div>
            <a href="{{ route('patients.create') }}"
                class="inline-flex items-center justify-center gap-2 px-4 py-3 md:py-2 min-h-[44px] bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-colors shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                <span class="hidden sm:inline">Tambah Pasien</span>
                <span class="sm:hidden">Tambah</span>
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 md:gap-4 mb-6">
        <button wire:click="$set('pregnancyFilter', 'all')"
            class="p-3 md:p-4 rounded-lg border-2 transition-all text-left min-h-[80px] {{ $pregnancyFilter === 'all' ? 'border-blue-500 bg-blue-50' : 'border-gray-200 hover:border-blue-300' }}">
            <p class="text-xs md:text-sm font-medium text-gray-600">Total Pasien</p>
            <p class="text-xl md:text-2xl font-bold text-gray-900 mt-1">{{ $stats['total'] }}</p>
        </button>

        <button wire:click="$set('pregnancyFilter', 'active')"
            class="p-4 rounded-lg border-2 transition-all text-left {{ $pregnancyFilter === 'active' ? 'border-green-500 bg-green-50' : 'border-gray-200 hover:border-green-300' }}">
            <p class="text-sm font-medium text-gray-600">Hamil Aktif</p>
            <p class="text-2xl font-bold text-green-600 mt-1">{{ $stats['active'] }}</p>
        </button>

        <button wire:click="$set('pregnancyFilter', 'completed')"
            class="p-4 rounded-lg border-2 transition-all text-left {{ $pregnancyFilter === 'completed' ? 'border-purple-500 bg-purple-50' : 'border-gray-200 hover:border-purple-300' }}">
            <p class="text-sm font-medium text-gray-600">Sudah Lahir</p>
            <p class="text-2xl font-bold text-purple-600 mt-1">{{ $stats['completed'] }}</p>
        </button>

        <button wire:click="$set('pregnancyFilter', 'none')"
            class="p-4 rounded-lg border-2 transition-all text-left {{ $pregnancyFilter === 'none' ? 'border-gray-500 bg-gray-50' : 'border-gray-200 hover:border-gray-300' }}">
            <p class="text-sm font-medium text-gray-600">Belum Hamil</p>
            <p class="text-2xl font-bold text-gray-600 mt-1">{{ $stats['none'] }}</p>
        </button>
    </div>

    <!-- Search & Filters -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
            <!-- Search Input -->
            <div class="md:col-span-5">
                <div class="relative">
                    <input type="text" wire:model.live.debounce.300ms="search"
                        placeholder="Cari NIK atau Nama pasien..."
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

            <!-- Risk Filter -->
            <div class="md:col-span-3">
                <select wire:model.live="riskFilter"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="all">Semua Risiko</option>
                    <option value="high">Risiko Tinggi</option>
                    <option value="low">Risiko Rendah</option>
                </select>
            </div>

            <!-- Per Page -->
            <div class="md:col-span-2">
                <select wire:model.live="perPage"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="10">10 per halaman</option>
                    <option value="20">20 per halaman</option>
                    <option value="50">50 per halaman</option>
                    <option value="100">100 per halaman</option>
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
            <span class="text-sm text-blue-700 font-medium">Memuat data...</span>
        </div>
    </div>

    <!-- Patient Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Pasien</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">NIK
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Status Kehamilan</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Usia Kehamilan</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Risiko</th>
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
                                <span class="text-sm text-gray-600">Memuat data...</span>
                            </div>
                        </td>
                    </tr>

                    <!-- Data Rows -->
                    @forelse($patients as $patient)
                        <tr class="hover:bg-blue-50 transition-colors cursor-pointer" wire:loading.remove
                            onclick="window.location='{{ route('patients.show', $patient->id) }}'">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div
                                        class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white font-bold flex-shrink-0">
                                        {{ strtoupper(substr($patient->name, 0, 2)) }}
                                    </div>
                                    <div class="ml-4">
                                        <div class="font-semibold text-gray-600 hover:text-gray-700">
                                            {{ $patient->name }}
                                        </div>
                                        <div class="text-sm text-gray-500">{{ $patient->phone ?? '-' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if ($patient->nik)
                                    <div class="text-sm font-medium text-gray-900">{{ $patient->nik }}</div>
                                @else
                                    <span
                                        class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800">
                                        Tanpa NIK
                                    </span>
                                @endif
                                <div class="text-xs text-gray-500">Umur: {{ $patient->age }} tahun</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $activePregnancy = $patient->pregnancies->where('status', 'Aktif')->first();
                                @endphp
                                @if ($activePregnancy)
                                    <span
                                        class="inline-flex items-center gap-1 px-2 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path>
                                            <path fill-rule="evenodd"
                                                d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm9.707 5.707a1 1 0 00-1.414-1.414L9 12.586l-1.293-1.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                        Hamil Aktif
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center px-2 py-1 bg-gray-100 text-gray-600 text-xs font-semibold rounded-full">
                                        Tidak Hamil
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if ($activePregnancy)
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $activePregnancy->gestational_age }} minggu</div>
                                    <div class="text-xs text-gray-500">{{ $activePregnancy->gravida }}</div>
                                @else
                                    <span class="text-sm text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if ($activePregnancy && $activePregnancy->ancVisits->isNotEmpty())
                                    @php
                                        $latestVisit = $activePregnancy->ancVisits->first();
                                        $riskCategory = $latestVisit->risk_category;
                                    @endphp
                                    <span
                                        class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full
                                        {{ $riskCategory === 'Ekstrem' ? 'bg-red-100 text-red-800' : '' }}
                                        {{ $riskCategory === 'Tinggi' ? 'bg-orange-100 text-orange-800' : '' }}
                                        {{ $riskCategory === 'Rendah' ? 'bg-green-100 text-green-800' : '' }}">
                                        {{ $riskCategory }}
                                    </span>
                                @else
                                    <span class="text-sm text-gray-400">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                                    </path>
                                </svg>
                                <p class="text-gray-500 font-medium mb-2">Tidak ada pasien ditemukan</p>
                                @if ($search || $pregnancyFilter !== 'all' || $riskFilter !== 'all')
                                    <button wire:click="clearFilters"
                                        class="text-sm text-blue-600 hover:text-blue-700 font-medium">
                                        Reset filter untuk melihat semua pasien
                                    </button>
                                @else
                                    <a href="{{ route('patients.create') }}"
                                        class="text-sm text-blue-600 hover:text-blue-700 font-medium">
                                        Tambah pasien pertama →
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if ($patients->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $patients->links() }}
            </div>
        @endif
    </div>

    <!-- Summary -->
    <div class="mt-4 text-sm text-gray-500 text-center">
        Menampilkan {{ $patients->firstItem() ?? 0 }} - {{ $patients->lastItem() ?? 0 }} dari
        {{ $patients->total() }} pasien
    </div>
</div>
