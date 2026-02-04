<x-dashboard-layout>
    <div class="mb-6" x-data="{ activeTab: 'general' }">
        <!-- Breadcrumb -->
        <x-breadcrumb :items="[['label' => 'Data Pasien', 'url' => route('patients.index')], ['label' => $patient->name]]" />

        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
            <div class="flex items-center gap-4">
                <a href="{{ route('patients.index') }}" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                    <x-heroicon-o-arrow-left class="w-6 h-6 text-gray-600" />
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Detail Pasien - Rekam Medis Terpadu</h1>
                    <p class="text-sm text-gray-500 mt-1">Informasi lengkap dan riwayat medis semua layanan</p>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="flex items-center gap-2 flex-wrap">
                <a href="{{ route('patients.edit', $patient) }}"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-colors shadow-sm">
                    <x-heroicon-o-pencil class="w-5 h-5" />
                    Edit Data
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
            <!-- Left Column - Patient Profile (Sticky) -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden sticky top-6">
                    <!-- Profile Header -->
                    <div class="bg-gradient-to-br from-blue-600 to-blue-800 p-6 text-white text-center">
                        <div class="w-24 h-24 rounded-full flex items-center justify-center text-4xl font-bold mx-auto mb-3 shadow-lg {{ $patient->gender === 'L' ? 'bg-gradient-to-br from-blue-400 to-blue-600' : 'bg-gradient-to-br from-pink-400 to-pink-600' }}">
                            {{ strtoupper(substr($patient->name, 0, 2)) }}
                        </div>
                        <h2 class="text-xl font-bold mb-1">{{ $patient->name }}</h2>
                        <p class="text-blue-100 text-sm">{{ $patient->age }} tahun</p>
                        
                        <!-- Gender & Category Badges -->
                        <div class="flex gap-2 justify-center mt-3">
                            @if($patient->gender === 'L')
                                <span class="px-3 py-1 bg-blue-500/50 backdrop-blur rounded-full text-xs font-semibold">
                                    👨 Laki-laki
                                </span>
                            @else
                                <span class="px-3 py-1 bg-pink-500/50 backdrop-blur rounded-full text-xs font-semibold">
                                    👩 Perempuan
                                </span>
                            @endif
                            
                            @php
                                $categoryIcons = [
                                    'Umum' => '📋',
                                    'Bumil' => '🤰',
                                    'Bayi/Balita' => '👶',
                                    'Lansia' => '👴'
                                ];
                            @endphp
                            <span class="px-3 py-1 bg-white/20 backdrop-blur rounded-full text-xs font-semibold">
                                {{ $categoryIcons[$patient->category] ?? '📋' }} {{ $patient->category }}
                            </span>
                        </div>
                    </div>

                    <!-- Demographics -->
                    <div class="p-6 space-y-4 text-sm">
                        <div>
                            <p class="text-gray-500 text-xs font-medium mb-1">NIK</p>
                            @if($patient->nik)
                                <p class="font-mono font-semibold text-gray-900">{{ $patient->nik }}</p>
                            @else
                                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-yellow-100 text-yellow-800">
                                    Tanpa NIK
                                </span>
                            @endif
                        </div>

                        @if($patient->no_bpjs)
                            <div>
                                <p class="text-gray-500 text-xs font-medium mb-1">No. BPJS</p>
                                <p class="font-mono text-gray-900">{{ $patient->no_bpjs }}</p>
                            </div>
                        @endif

                        <div>
                            <p class="text-gray-500 text-xs font-medium mb-1">Tanggal Lahir</p>
                            <p class="text-gray-900">{{ $patient->dob->locale('id')->isoFormat('D MMMM YYYY') }}</p>
                        </div>

                        <div>
                            <p class="text-gray-500 text-xs font-medium mb-1">Golongan Darah</p>
                            <span class="inline-flex items-center px-3 py-1 text-sm font-bold text-red-700 bg-red-100 rounded-full">
                                {{ $patient->blood_type }}
                            </span>
                        </div>

                        @if($patient->phone)
                            <div>
                                <p class="text-gray-500 text-xs font-medium mb-1">Telepon</p>
                                <p class="text-gray-900">{{ $patient->phone }}</p>
                            </div>
                        @endif

                        <div>
                            <p class="text-gray-500 text-xs font-medium mb-1">Alamat</p>
                            <p class="text-gray-900">{{ $patient->address }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column - Tabs Content -->
            <div class="lg:col-span-3">
                <!-- Tab Navigation -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-6 overflow-hidden">
                    <div class="flex overflow-x-auto">
                        <!-- Tab 1: Riwayat Umum (Always visible) -->
                        <button @click="activeTab = 'general'"
                            :class="activeTab === 'general' ? 'bg-blue-50 text-blue-700 border-b-2 border-blue-600' : 'text-gray-600 hover:bg-gray-50'"
                            class="flex-1 min-w-max px-6 py-4 font-semibold transition-colors flex items-center justify-center gap-2">
                            <x-heroicon-o-clipboard-document-check class="w-5 h-5" />
                            <span>Riwayat Umum</span>
                        </button>

                        <!-- Tab 2: Riwayat Kehamilan/ANC (Only for female) -->
                        @if($patient->gender === 'P')
                            <button @click="activeTab = 'anc'"
                                :class="activeTab === 'anc' ? 'bg-pink-50 text-pink-700 border-b-2 border-pink-600' : 'text-gray-600 hover:bg-gray-50'"
                                class="flex-1 min-w-max px-6 py-4 font-semibold transition-colors flex items-center justify-center gap-2">
                                <x-heroicon-o-heart class="w-5 h-5" />
                                <span>Kehamilan/ANC</span>
                            </button>
                        @endif

                        <!-- Tab 3: Riwayat Persalinan & Nifas (Only for female) -->
                        @if($patient->gender === 'P')
                            <button @click="activeTab = 'delivery'"
                                :class="activeTab === 'delivery' ? 'bg-purple-50 text-purple-700 border-b-2 border-purple-600' : 'text-gray-600 hover:bg-gray-50'"
                                class="flex-1 min-w-max px-6 py-4 font-semibold transition-colors flex items-center justify-center gap-2">
                                <x-heroicon-o-sparkles class="w-5 h-5" />
                                <span>Persalinan & Nifas</span>
                            </button>
                        @endif

                        <!-- Tab 4: Riwayat KB (Only for female, reproductive age) -->
                        @if($patient->gender === 'P' && $patient->age >= 15 && $patient->age <= 49)
                            <button @click="activeTab = 'kb'"
                                :class="activeTab === 'kb' ? 'bg-green-50 text-green-700 border-b-2 border-green-600' : 'text-gray-600 hover:bg-gray-50'"
                                class="flex-1 min-w-max px-6 py-4 font-semibold transition-colors flex items-center justify-center gap-2">
                                <x-heroicon-o-check-circle class="w-5 h-5" />
                                <span>Riwayat KB</span>
                            </button>
                        @endif

                        <!-- Tab 5: Riwayat Imunisasi (Only for age < 5) -->
                        @if($patient->age < 5)
                            <button @click="activeTab = 'immunization'"
                                :class="activeTab === 'immunization' ? 'bg-orange-50 text-orange-700 border-b-2 border-orange-600' : 'text-gray-600 hover:bg-gray-50'"
                                class="flex-1 min-w-max px-6 py-4 font-semibold transition-colors flex items-center justify-center gap-2">
                                <x-heroicon-o-shield-check class="w-5 h-5" />
                                <span>Imunisasi</span>
                            </button>
                        @endif
                    </div>
                </div>

                <!-- Tab Content -->
                <div class="space-y-6">
                    <!-- Tab 1: Riwayat Umum -->
                    <div x-show="activeTab === 'general'" x-transition>
                        @include('patients.tabs.general')
                    </div>

                    <!-- Tab 2: Riwayat Kehamilan/ANC -->
                    @if($patient->gender === 'P')
                        <div x-show="activeTab === 'anc'" x-transition>
                            @include('patients.tabs.anc')
                        </div>
                    @endif

                    <!-- Tab 3: Riwayat Persalinan & Nifas -->
                    @if($patient->gender === 'P')
                        <div x-show="activeTab === 'delivery'" x-transition>
                            @include('patients.tabs.delivery')
                        </div>
                    @endif

                    <!-- Tab 4: Riwayat KB -->
                    @if($patient->gender === 'P' && $patient->age >= 15 && $patient->age <= 49)
                        <div x-show="activeTab === 'kb'" x-transition>
                            @include('patients.tabs.kb')
                        </div>
                    @endif

                    <!-- Tab 5: Riwayat Imunisasi -->
                    @if($patient->age < 5)
                        <div x-show="activeTab === 'immunization'" x-transition>
                            @include('patients.tabs.immunization')
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-dashboard-layout>
