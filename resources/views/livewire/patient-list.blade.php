<div>
    <!-- Breadcrumb -->
    <x-breadcrumb :items="[['label' => 'Data Pasien']]" />

    <!-- Header & Search Section -->
    <div class="mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Data Pasien (Universal)</h2>
                <p class="text-sm text-gray-500 mt-1">Kelola semua data pasien (Pria, Wanita, Anak, Lansia)</p>
            </div>
            <a href="{{ route('patients.create') }}"
                class="inline-flex items-center justify-center gap-2 px-4 py-3 md:py-2 min-h-[44px] bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-colors shadow-sm">
                <x-heroicon-o-plus class="w-5 h-5" />
                <span class="hidden sm:inline">Tambah Pasien</span>
                <span class="sm:hidden">Tambah</span>
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-2 md:grid-cols-5 gap-3 md:gap-4 mb-6">
        <!-- Total -->
        <button wire:click="$set('categoryFilter', 'all'); $set('genderFilter', 'all')"
            class="p-3 md:p-4 rounded-lg border-2 transition-all text-left min-h-[80px] {{ $categoryFilter === 'all' && $genderFilter === 'all' ? 'border-blue-500 bg-blue-50' : 'border-gray-200 hover:border-blue-300' }}">
            <p class="text-xs md:text-sm font-medium text-gray-600">Total Pasien</p>
            <p class="text-xl md:text-2xl font-bold text-gray-900 mt-1">{{ $stats['total'] }}</p>
        </button>

        <!-- Category Filters -->
        <button wire:click="$set('categoryFilter', 'Umum')"
            class="p-3 md:p-4 rounded-lg border-2 transition-all text-left {{ $categoryFilter === 'Umum' ? 'border-indigo-500 bg-indigo-50' : 'border-gray-200 hover:border-indigo-300' }}">
            <div class="flex items-center gap-2 mb-1">
                <x-heroicon-o-clipboard-document-check class="w-4 h-4 text-indigo-600" />
                <p class="text-xs md:text-sm font-medium text-gray-600">Umum</p>
            </div>
            <p class="text-xl md:text-2xl font-bold text-indigo-600 mt-1">{{ $stats['categories']['Umum'] }}</p>
        </button>

        <button wire:click="$set('categoryFilter', 'Bumil')"
            class="p-3 md:p-4 rounded-lg border-2 transition-all text-left {{ $categoryFilter === 'Bumil' ? 'border-pink-500 bg-pink-50' : 'border-gray-200 hover:border-pink-300' }}">
            <div class="flex items-center gap-2 mb-1">
                <x-heroicon-o-heart class="w-4 h-4 text-pink-600" />
                <p class="text-xs md:text-sm font-medium text-gray-600">Bumil</p>
            </div>
            <p class="text-xl md:text-2xl font-bold text-pink-600 mt-1">{{ $stats['categories']['Bumil'] }}</p>
        </button>

        <button wire:click="$set('categoryFilter', 'Bayi/Balita')"
            class="p-3 md:p-4 rounded-lg border-2 transition-all text-left {{ $categoryFilter === 'Bayi/Balita' ? 'border-green-500 bg-green-50' : 'border-gray-200 hover:border-green-300' }}">
            <div class="flex items-center gap-2 mb-1">
                <x-heroicon-o-sparkles class="w-4 h-4 text-green-600" />
                <p class="text-xs md:text-sm font-medium text-gray-600">Bayi/Balita</p>
            </div>
            <p class="text-xl md:text-2xl font-bold text-green-600 mt-1">{{ $stats['categories']['Bayi/Balita'] }}</p>
        </button>

        <button wire:click="$set('categoryFilter', 'Lansia')"
            class="p-3 md:p-4 rounded-lg border-2 transition-all text-left {{ $categoryFilter === 'Lansia' ? 'border-purple-500 bg-purple-50' : 'border-gray-200 hover:border-purple-300' }}">
            <div class="flex items-center gap-2 mb-1">
                <x-heroicon-o-user-circle class="w-4 h-4 text-purple-600" />
                <p class="text-xs md:text-sm font-medium text-gray-600">Lansia</p>
            </div>
            <p class="text-xl md:text-2xl font-bold text-purple-600 mt-1">{{ $stats['categories']['Lansia'] }}</p>
        </button>
    </div>

    <!-- Search & Filters -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
            <!-- Search Input -->
            <div class="md:col-span-5">
                <div class="relative">
                    <input type="text" wire:model.live.debounce.300ms="search"
                        placeholder="Cari NIK, Nama, atau Telepon..."
                        class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <x-heroicon-o-magnifying-glass class="absolute left-3 top-2.5 w-5 h-5 text-gray-400" />
                    @if ($search)
                        <button wire:click="$set('search', '')"
                            class="absolute right-3 top-2.5 text-gray-400 hover:text-gray-600">
                            <x-heroicon-o-x-mark class="w-5 h-5" />
                        </button>
                    @endif
                </div>
            </div>

            <!-- Gender Filter -->
            <div class="md:col-span-3">
                <select wire:model.live="genderFilter"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="all">Semua Jenis Kelamin</option>
                    <option value="L">Laki-laki</option>
                    <option value="P">Perempuan</option>
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
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
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
                            Nama Pasien</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">NIK</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">JK</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Umur</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Kategori</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Alamat</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($patients as $patient)
                        <tr class="hover:bg-blue-50 transition-colors cursor-pointer"
                            onclick="window.location='{{ $patient->getShowRoute() }}'">
                            <!-- Nama Pasien -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div
                                        class="w-10 h-10 rounded-full flex items-center justify-center text-white font-bold flex-shrink-0 {{ $patient->gender === 'L' ? 'bg-gradient-to-br from-blue-400 to-blue-600' : 'bg-gradient-to-br from-pink-400 to-pink-600' }}">
                                        {{ strtoupper(substr($patient->name, 0, 2)) }}
                                    </div>
                                    <div class="ml-4">
                                        <div class="font-semibold text-gray-900">
                                            {{ $patient->name }}
                                        </div>
                                        <div class="text-sm text-gray-500">{{ $patient->phone ?? 'No Telp: -' }}</div>
                                    </div>
                                </div>
                            </td>

                            <!-- NIK -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if ($patient->nik)
                                    <div class="text-sm font-medium text-gray-900">{{ $patient->nik }}</div>
                                @else
                                    <span
                                        class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800">
                                        Tanpa NIK
                                    </span>
                                @endif
                            </td>

                            <!-- Jenis Kelamin -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if ($patient->gender === 'L')
                                    <span
                                        class="inline-flex items-center gap-1 px-2.5 py-1 bg-blue-100 text-blue-800 text-xs font-semibold rounded-full">
                                        <x-heroicon-o-user class="w-3 h-3" />
                                        Laki-laki
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center gap-1 px-2.5 py-1 bg-pink-100 text-pink-800 text-xs font-semibold rounded-full">
                                        <x-heroicon-o-user class="w-3 h-3" />
                                        Perempuan
                                    </span>
                                @endif
                            </td>

                            <!-- Umur -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $patient->getFormattedAge() }}</div>
                            </td>

                            <!-- Kategori -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $categoryBadges = [
                                        'Umum' => ['bg' => 'bg-indigo-100', 'text' => 'text-indigo-800', 'icon' => 'clipboard-document-check'],
                                        'Bumil' => ['bg' => 'bg-pink-100', 'text' => 'text-pink-800', 'icon' => 'heart'],
                                        'Bayi/Balita' => ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'icon' => 'sparkles'],
                                        'Lansia' => ['bg' => 'bg-purple-100', 'text' => 'text-purple-800', 'icon' => 'user-circle'],
                                    ];
                                    $badge = $categoryBadges[$patient->category] ?? $categoryBadges['Umum'];
                                @endphp
                                <span
                                    class="inline-flex items-center gap-1 px-2.5 py-1 {{ $badge['bg'] }} {{ $badge['text'] }} text-xs font-semibold rounded-full">
                                    @if($badge['icon'] === 'clipboard-document-check')
                                        <x-heroicon-o-clipboard-document-check class="w-3 h-3" />
                                    @elseif($badge['icon'] === 'heart')
                                        <x-heroicon-o-heart class="w-3 h-3" />
                                    @elseif($badge['icon'] === 'sparkles')
                                        <x-heroicon-o-sparkles class="w-3 h-3" />
                                    @else
                                        <x-heroicon-o-user-circle class="w-3 h-3" />
                                    @endif
                                    {{ $patient->category }}
                                </span>
                            </td>

                            <!-- Alamat -->
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900 max-w-xs truncate">{{ $patient->address ?? '-' }}</div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center text-gray-500">
                                    <x-heroicon-o-user-group class="w-12 h-12 mb-2 text-gray-300" />
                                    <p class="text-sm font-medium">Tidak ada data pasien</p>
                                    <p class="text-xs mt-1">Silakan tambah pasien baru atau ubah filter pencarian</p>
                                </div>
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
</div>
