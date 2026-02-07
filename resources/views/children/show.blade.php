<x-dashboard-layout>
    <div class="mb-6" x-data="{ activeTab: 'immunization' }">
        <!-- Breadcrumb -->
        <x-breadcrumb :items="[['label' => 'Data Pasien', 'url' => route('patients.index')], ['label' => $child->name]]" />

        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
            <div class="flex items-center gap-4">
                <a href="{{ route('patients.index') }}" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                    <x-heroicon-o-arrow-left class="w-6 h-6 text-gray-600" />
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Detail Pasien Anak</h1>
                    <p class="text-sm text-gray-500 mt-1">Informasi lengkap dan riwayat medis anak</p>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="flex items-center gap-2 flex-wrap">
                <a href="{{ route('imunisasi.kunjungan', $child) }}"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg transition-colors shadow-sm">
                    <x-heroicon-o-shield-check class="w-5 h-5" />
                    Daftar Imunisasi
                </a>
                <a href="{{ route('children.general-visit', $child) }}"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-colors shadow-sm">
                    <x-heroicon-o-clipboard-document-check class="w-5 h-5" />
                    Daftar Poli Umum
                </a>
            </div>
        </div>

        <!-- Flash Messages -->
        @if (session('success'))
            <div class="mb-6 bg-green-50 border-l-4 border-green-500 rounded-lg p-4 shadow-sm" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)">
                <div class="flex items-center">
                    <x-heroicon-o-check-circle class="w-6 h-6 text-green-600 flex-shrink-0" />
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-900">{{ session('success') }}</p>
                    </div>
                    <button @click="show = false" class="ml-auto text-green-500 hover:text-green-700">
                        <x-heroicon-o-x-mark class="w-5 h-5" />
                    </button>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
            <!-- Left Column - Child Profile (Sticky) -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden sticky top-6">
                    <!-- Profile Header -->
                    <div class="bg-gradient-to-br from-green-500 to-teal-600 p-6 text-white text-center">
                        <div class="w-24 h-24 rounded-full flex items-center justify-center text-4xl font-bold mx-auto mb-3 shadow-lg {{ $child->gender === 'L' ? 'bg-gradient-to-br from-blue-400 to-blue-600' : 'bg-gradient-to-br from-pink-400 to-pink-600' }}">
                            {{ strtoupper(substr($child->name, 0, 2)) }}
                        </div>
                        <h2 class="text-xl font-bold mb-1">{{ $child->name }}</h2>
                        <p class="text-green-100 text-sm">{{ $child->getDetailedAge() }}</p>

                        <!-- Gender Badge -->
                        <div class="flex gap-2 justify-center mt-3">
                            @if($child->gender === 'L')
                                <span class="px-3 py-1 bg-blue-500/50 backdrop-blur rounded-full text-xs font-semibold">
                                    👦 Laki-laki
                                </span>
                            @else
                                <span class="px-3 py-1 bg-pink-500/50 backdrop-blur rounded-full text-xs font-semibold">
                                    👧 Perempuan
                                </span>
                            @endif
                            <span class="px-3 py-1 bg-white/20 backdrop-blur rounded-full text-xs font-semibold">
                                👶 Bayi/Balita
                            </span>
                        </div>
                    </div>

                    <!-- Child Info -->
                    <div class="p-6 space-y-4 text-sm">
                        <div>
                            <p class="text-gray-500 text-xs font-medium mb-1">No. Rekam Medis</p>
                            <p class="font-mono font-semibold text-gray-900">{{ $child->no_rm ?? '-' }}</p>
                        </div>

                        @if($child->nik)
                            <div>
                                <p class="text-gray-500 text-xs font-medium mb-1">NIK Anak</p>
                                <p class="font-mono text-gray-900">{{ $child->nik }}</p>
                            </div>
                        @endif

                        <div>
                            <p class="text-gray-500 text-xs font-medium mb-1">Tempat, Tanggal Lahir</p>
                            <p class="text-gray-900">{{ $child->pob }}, {{ $child->dob?->locale('id')->isoFormat('D MMMM YYYY') }}</p>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <p class="text-gray-500 text-xs font-medium mb-1">BB Lahir</p>
                                <p class="text-gray-900 font-medium">{{ $child->birth_weight ? number_format($child->birth_weight, 0) . ' gr' : '-' }}</p>
                            </div>
                            <div>
                                <p class="text-gray-500 text-xs font-medium mb-1">PB Lahir</p>
                                <p class="text-gray-900 font-medium">{{ $child->birth_height ? number_format($child->birth_height, 1) . ' cm' : '-' }}</p>
                            </div>
                        </div>

                        <hr class="border-gray-200">

                        <!-- Parent Info -->
                        <div>
                            <p class="text-gray-500 text-xs font-medium mb-1">Nama Ibu/Wali</p>
                            <p class="text-gray-900 font-medium">{{ $child->parent_display_name }}</p>
                        </div>

                        <div>
                            <p class="text-gray-500 text-xs font-medium mb-1">Telepon</p>
                            <p class="text-gray-900">{{ $child->parent_display_phone }}</p>
                        </div>

                        <div>
                            <p class="text-gray-500 text-xs font-medium mb-1">Alamat</p>
                            <p class="text-gray-900">{{ $child->parent_display_address }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column - Tabs Content -->
            <div class="lg:col-span-3">
                <!-- Tab Navigation -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-6 overflow-hidden">
                    <div class="flex overflow-x-auto">
                        <!-- Tab 1: Riwayat Imunisasi -->
                        <button @click="activeTab = 'immunization'"
                            :class="activeTab === 'immunization' ? 'bg-green-50 text-green-700 border-b-2 border-green-600' : 'text-gray-600 hover:bg-gray-50'"
                            class="flex-1 min-w-max px-6 py-4 font-semibold transition-colors flex items-center justify-center gap-2">
                            <x-heroicon-o-shield-check class="w-5 h-5" />
                            <span>Riwayat Imunisasi</span>
                        </button>

                        <!-- Tab 2: Riwayat Umum (Poli Umum) -->
                        <button @click="activeTab = 'general'"
                            :class="activeTab === 'general' ? 'bg-blue-50 text-blue-700 border-b-2 border-blue-600' : 'text-gray-600 hover:bg-gray-50'"
                            class="flex-1 min-w-max px-6 py-4 font-semibold transition-colors flex items-center justify-center gap-2">
                            <x-heroicon-o-clipboard-document-check class="w-5 h-5" />
                            <span>Riwayat Umum</span>
                        </button>

                        <!-- Tab 3: Pertumbuhan -->
                        <button @click="activeTab = 'growth'"
                            :class="activeTab === 'growth' ? 'bg-purple-50 text-purple-700 border-b-2 border-purple-600' : 'text-gray-600 hover:bg-gray-50'"
                            class="flex-1 min-w-max px-6 py-4 font-semibold transition-colors flex items-center justify-center gap-2">
                            <x-heroicon-o-chart-bar class="w-5 h-5" />
                            <span>Pertumbuhan</span>
                        </button>
                    </div>
                </div>

                <!-- Tab Content -->
                <div class="space-y-6">
                    <!-- Tab 1: Riwayat Imunisasi -->
                    <div x-show="activeTab === 'immunization'" x-transition>
                        @include('children.tabs.immunization')
                    </div>

                    <!-- Tab 2: Riwayat Umum -->
                    <div x-show="activeTab === 'general'" x-transition>
                        @include('children.tabs.general')
                    </div>

                    <!-- Tab 3: Pertumbuhan -->
                    <div x-show="activeTab === 'growth'" x-transition>
                        @include('children.tabs.growth')
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-dashboard-layout>
