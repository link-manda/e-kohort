<x-dashboard-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <a href="{{ route('patients.index') }}" class="text-gray-600 hover:text-gray-800">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Detail Pasien</h1>
                    <p class="text-sm text-gray-600 mt-1">Informasi lengkap pasien dan riwayat kehamilan</p>
                </div>
            </div>
            <div class="flex items-center space-x-2">
                <a href="{{ route('patients.edit', $patient) }}"
                    class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                        </path>
                    </svg>
                    <span>Edit Data</span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column - Patient Profile -->
        <div class="space-y-6">
            <!-- Profile Card -->
            <div class="bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl shadow-lg p-6 text-white">
                <div class="text-center">
                    <div
                        class="w-24 h-24 bg-white rounded-full flex items-center justify-center text-blue-600 text-4xl font-bold mx-auto mb-4">
                        {{ strtoupper(substr($patient->name, 0, 1)) }}
                    </div>
                    <h2 class="text-2xl font-bold mb-1">{{ $patient->name }}</h2>
                    <p class="text-blue-100 text-sm mb-4">{{ $patient->age }} tahun</p>

                    @if ($patient->activePregnancy)
                        <div class="bg-white/20 backdrop-blur rounded-lg px-4 py-2 inline-block">
                            <p class="text-xs text-blue-100">Status Kehamilan</p>
                            <p class="font-bold">AKTIF</p>
                        </div>
                    @else
                        <div class="bg-white/20 backdrop-blur rounded-lg px-4 py-2 inline-block">
                            <p class="text-xs text-blue-100">Status</p>
                            <p class="font-bold">Tidak Hamil</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Contact Info -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="font-bold text-gray-800 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                        </path>
                    </svg>
                    Kontak & Identitas
                </h3>
                <div class="space-y-3 text-sm">
                    <div>
                        <p class="text-gray-600 mb-1">NIK</p>
                        <p class="font-mono font-semibold text-gray-800">{{ $patient->nik }}</p>
                    </div>
                    @if ($patient->no_kk)
                        <div>
                            <p class="text-gray-600 mb-1">No. KK</p>
                            <p class="font-mono font-semibold text-gray-800">{{ $patient->no_kk }}</p>
                        </div>
                    @endif
                    @if ($patient->no_bpjs)
                        <div>
                            <p class="text-gray-600 mb-1">No. BPJS</p>
                            <p class="font-mono font-semibold text-gray-800">{{ $patient->no_bpjs }}</p>
                        </div>
                    @endif
                    <div>
                        <p class="text-gray-600 mb-1">Tanggal Lahir</p>
                        <p class="font-semibold text-gray-800">
                            {{ $patient->dob->locale('id')->isoFormat('D MMMM YYYY') }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600 mb-1">Golongan Darah</p>
                        <span class="inline-block px-3 py-1 text-xs font-bold text-blue-700 bg-blue-100 rounded">
                            {{ $patient->blood_type }}
                        </span>
                    </div>
                    @if ($patient->phone)
                        <div>
                            <p class="text-gray-600 mb-1">Telepon / WhatsApp</p>
                            <a href="https://wa.me/{{ $patient->phone }}" target="_blank"
                                class="font-semibold text-green-600 hover:text-green-700">
                                {{ $patient->phone }}
                            </a>
                        </div>
                    @endif
                    <div>
                        <p class="text-gray-600 mb-1">Alamat</p>
                        <p class="text-gray-800">{{ $patient->address }}</p>
                    </div>
                </div>
            </div>

            <!-- Husband Info -->
            @if ($patient->husband_name)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="font-bold text-gray-800 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        Data Suami
                    </h3>
                    <div class="space-y-3 text-sm">
                        <div>
                            <p class="text-gray-600 mb-1">Nama Lengkap</p>
                            <p class="font-semibold text-gray-800">{{ $patient->husband_name }}</p>
                        </div>
                        @if ($patient->husband_nik)
                            <div>
                                <p class="text-gray-600 mb-1">NIK</p>
                                <p class="font-mono text-gray-800">{{ $patient->husband_nik }}</p>
                            </div>
                        @endif
                        @if ($patient->husband_job)
                            <div>
                                <p class="text-gray-600 mb-1">Pekerjaan</p>
                                <p class="text-gray-800">{{ $patient->husband_job }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        <!-- Right Column - Pregnancy History -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Active Pregnancy -->
            @if ($patient->activePregnancy)
                <div class="bg-white rounded-xl shadow-sm border-2 border-green-200 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="font-bold text-gray-800 text-lg flex items-center">
                            <svg class="w-6 h-6 mr-2 text-green-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Kehamilan Aktif
                        </h3>
                        <span class="px-3 py-1 text-xs font-semibold text-green-700 bg-green-100 rounded-full">
                            {{ $patient->activePregnancy->status }}
                        </span>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                        <div class="bg-blue-50 rounded-lg p-4">
                            <p class="text-xs text-blue-600 mb-1">Gravida</p>
                            <p class="text-lg font-bold text-blue-900">{{ $patient->activePregnancy->gravida }}</p>
                        </div>
                        <div class="bg-purple-50 rounded-lg p-4">
                            <p class="text-xs text-purple-600 mb-1">UK Sekarang</p>
                            <p class="text-lg font-bold text-purple-900">
                                {{ $patient->activePregnancy->gestational_age }} minggu</p>
                        </div>
                        <div class="bg-green-50 rounded-lg p-4">
                            <p class="text-xs text-green-600 mb-1">HPHT</p>
                            <p class="text-sm font-bold text-green-900">
                                {{ $patient->activePregnancy->hpht->format('d/m/Y') }}</p>
                        </div>
                        <div class="bg-orange-50 rounded-lg p-4">
                            <p class="text-xs text-orange-600 mb-1">HPL</p>
                            <p class="text-sm font-bold text-orange-900">
                                {{ $patient->activePregnancy->hpl->format('d/m/Y') }}</p>
                        </div>
                    </div>

                    <!-- Recent ANC Visits -->
                    @if ($patient->activePregnancy->ancVisits->count() > 0)
                        <h4 class="font-semibold text-gray-800 mb-3">Riwayat Kunjungan ANC</h4>
                        <div class="space-y-3">
                            @foreach ($patient->activePregnancy->ancVisits->take(5) as $visit)
                                <div
                                    class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                                    <div class="flex items-center space-x-4">
                                        <div
                                            class="w-12 h-12 bg-blue-500 rounded-lg flex items-center justify-center text-white font-bold">
                                            {{ $visit->visit_code }}
                                        </div>
                                        <div>
                                            <p class="font-semibold text-gray-800">
                                                {{ $visit->visit_date->locale('id')->isoFormat('D MMMM YYYY') }}</p>
                                            <p class="text-sm text-gray-600">UK: {{ $visit->gestational_age }} minggu
                                                • Trimester {{ $visit->trimester }}</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        @if ($visit->map_score)
                                            <p class="text-sm text-gray-600">MAP: {{ $visit->map_score }}</p>
                                        @endif
                                        <span
                                            class="inline-block px-2 py-1 text-xs font-semibold rounded
                                            @if ($visit->risk_category === 'Ekstrem') text-red-700 bg-red-100
                                            @elseif($visit->risk_category === 'Tinggi') text-orange-700 bg-orange-100
                                            @else text-green-700 bg-green-100 @endif">
                                            {{ $visit->risk_category }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <button
                            class="mt-4 w-full bg-blue-50 hover:bg-blue-100 text-blue-700 font-semibold px-4 py-2 rounded-lg transition-colors">
                            Lihat Semua Kunjungan ({{ $patient->activePregnancy->ancVisits->count() }})
                        </button>
                    @else
                        <div class="text-center py-8 bg-gray-50 rounded-lg">
                            <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                </path>
                            </svg>
                            <p class="text-gray-500 mb-4">Belum ada kunjungan ANC</p>
                            <a href="{{ route('anc-visits.create', $patient->activePregnancy->id) }}"
                                class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition-colors">
                                Tambah Kunjungan ANC
                            </a>
                        </div>
                    @endif
                </div>
            @else
                <div class="bg-blue-50 border-2 border-blue-200 rounded-xl p-8 text-center">
                    <svg class="w-16 h-16 text-blue-400 mx-auto mb-4" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                        </path>
                    </svg>
                    <h3 class="text-lg font-bold text-blue-900 mb-2">Tidak Ada Kehamilan Aktif</h3>
                    <p class="text-blue-700 mb-4">Pasien saat ini tidak dalam status kehamilan</p>
                    <a href="{{ route('pregnancies.create', $patient->id) }}"
                        class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg transition-colors">
                        Daftarkan Kehamilan Baru
                    </a>
                </div>
            @endif

            <!-- All Pregnancy History -->
            @if ($patient->pregnancies->count() > 1 || !$patient->activePregnancy)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="font-bold text-gray-800 text-lg mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-gray-600" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Riwayat Kehamilan Sebelumnya
                    </h3>

                    @if ($patient->pregnancies->where('status', '!=', 'Aktif')->count() > 0)
                        <div class="space-y-3">
                            @foreach ($patient->pregnancies->where('status', '!=', 'Aktif') as $pregnancy)
                                <div class="p-4 bg-gray-50 rounded-lg">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="font-semibold text-gray-800">{{ $pregnancy->gravida }}</span>
                                        <span
                                            class="px-2 py-1 text-xs font-semibold rounded
                                            @if ($pregnancy->status === 'Lahir') text-green-700 bg-green-100
                                            @else text-red-700 bg-red-100 @endif">
                                            {{ $pregnancy->status }}
                                        </span>
                                    </div>
                                    <p class="text-sm text-gray-600">
                                        HPHT: {{ $pregnancy->hpht->format('d/m/Y') }} •
                                        HPL: {{ $pregnancy->hpl->format('d/m/Y') }}
                                    </p>
                                    @if ($pregnancy->ancVisits->count() > 0)
                                        <p class="text-xs text-gray-500 mt-1">
                                            {{ $pregnancy->ancVisits->count() }} kunjungan ANC tercatat
                                        </p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-4">Tidak ada riwayat kehamilan sebelumnya</p>
                    @endif
                </div>
            @endif
        </div>
    </div>

    <!-- Success Message -->
    @if (session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
            class="fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg flex items-center space-x-2 z-50">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif
</x-dashboard-layout>
