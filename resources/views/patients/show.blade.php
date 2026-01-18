<x-dashboard-layout>
    <div class="mb-6">
        <!-- Breadcrumb -->
        <x-breadcrumb :items="[['label' => 'Data Pasien', 'url' => route('patients.index')], ['label' => $patient->name]]" />

        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div class="flex items-center gap-4">
                <a href="{{ route('patients.index') }}" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                    <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Detail Pasien</h1>
                    <p class="text-sm text-gray-500 mt-1">Informasi lengkap dan riwayat kehamilan</p>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="flex items-center gap-2">
                <!-- Print Button -->
                <a href="{{ route('patients.print', $patient) }}" target="_blank"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-lg transition-colors shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                        </path>
                    </svg>
                    Cetak
                </a>

                @if ($patient->activePregnancy && $patient->activePregnancy->status === 'Aktif')
                    <a href="{{ route('anc-visits.create', $patient->activePregnancy->id) }}"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg transition-colors shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Tambah Kunjungan
                    </a>
                @elseif (!$patient->activePregnancy)
                    <a href="{{ route('pregnancies.create', $patient->id) }}"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white font-semibold rounded-lg transition-colors shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Daftarkan Kehamilan
                    </a>
                @endif

                <a href="{{ route('patients.edit', $patient) }}"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-colors shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                        </path>
                    </svg>
                    Edit Data
                </a>
            </div>
        </div>
    </div>

    <!-- Flash Messages -->
    @if (session('success'))
        <div class="mb-6 bg-green-50 border-l-4 border-green-500 rounded-lg p-4 shadow-sm animate-pulse"
            x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-bold text-green-900">Berhasil!</h3>
                    <p class="text-sm text-green-700 mt-1">{{ session('success') }}</p>
                </div>
                <button @click="show = false" class="ml-auto text-green-500 hover:text-green-700">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                            clip-rule="evenodd"></path>
                    </svg>
                </button>
            </div>
        </div>
    @endif

    @if (session('error'))
        <div class="mb-6 bg-red-50 border-l-4 border-red-500 rounded-lg p-4 shadow-sm" x-data="{ show: true }"
            x-show="show" x-init="setTimeout(() => show = false, 5000)">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-bold text-red-900">Error!</h3>
                    <p class="text-sm text-red-700 mt-1">{{ session('error') }}</p>
                </div>
                <button @click="show = false" class="ml-auto text-red-500 hover:text-red-700">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                            clip-rule="evenodd"></path>
                    </svg>
                </button>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column - Patient Profile -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Profile Card -->
            <div class="bg-gradient-to-br from-blue-600 to-blue-800 rounded-xl shadow-lg p-6 text-white">
                <div class="text-center">
                    <div
                        class="w-24 h-24 bg-white rounded-full flex items-center justify-center text-blue-600 text-4xl font-bold mx-auto mb-4 shadow-lg">
                        {{ strtoupper(substr($patient->name, 0, 2)) }}
                    </div>
                    <h2 class="text-2xl font-bold mb-1">{{ $patient->name }}</h2>
                    <p class="text-blue-100 text-sm mb-4">{{ $patient->age }} tahun</p>

                    @if ($patient->activePregnancy)
                        @if ($patient->activePregnancy->status === 'Lahir')
                            <div class="bg-blue-500 backdrop-blur rounded-lg px-4 py-3 inline-block">
                                <p class="text-xs text-blue-100 mb-1">Status Kehamilan</p>
                                <p class="font-bold text-lg flex items-center justify-center gap-2">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path>
                                        <path fill-rule="evenodd"
                                            d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                    LAHIR
                                </p>
                            </div>
                        @else
                            <div class="bg-green-500 backdrop-blur rounded-lg px-4 py-3 inline-block">
                                <p class="text-xs text-green-100 mb-1">Status Kehamilan</p>
                                <p class="font-bold text-lg flex items-center justify-center gap-2">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                    AKTIF
                                </p>
                            </div>
                        @endif
                    @else
                        <div class="bg-white/20 backdrop-blur rounded-lg px-4 py-3 inline-block">
                            <p class="text-xs text-blue-100 mb-1">Status</p>
                            <p class="font-bold">Tidak Hamil</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Demographics -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="font-bold text-gray-900 mb-4 flex items-center text-lg">
                    <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2">
                        </path>
                    </svg>
                    Data Identitas
                </h3>
                <div class="space-y-4 text-sm">
                    <div>
                        <p class="text-gray-500 text-xs font-medium mb-1">NIK</p>
                        @if ($patient->nik)
                            <p class="font-mono font-semibold text-gray-900 text-base">{{ $patient->nik }}</p>
                        @else
                            <div class="flex items-center gap-2">
                                <span
                                    class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-yellow-100 text-yellow-800">
                                    Tidak ada NIK
                                </span>
                                <span class="text-xs text-gray-500">Pasien tidak memiliki NIK</span>
                            </div>
                        @endif
                    </div>
                    @if ($patient->no_kk)
                        <div>
                            <p class="text-gray-500 text-xs font-medium mb-1">No. KK</p>
                            <p class="font-mono text-gray-900">{{ $patient->no_kk }}</p>
                        </div>
                    @endif
                    @if ($patient->no_bpjs)
                        <div>
                            <p class="text-gray-500 text-xs font-medium mb-1">No. BPJS</p>
                            <p class="font-mono text-gray-900">{{ $patient->no_bpjs }}</p>
                        </div>
                    @endif
                    <div>
                        <p class="text-gray-500 text-xs font-medium mb-1">Tanggal Lahir</p>
                        <p class="text-gray-900 font-medium">
                            {{ $patient->dob->locale('id')->isoFormat('D MMMM YYYY') }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500 text-xs font-medium mb-1">Golongan Darah</p>
                        <span
                            class="inline-flex items-center px-3 py-1 text-sm font-bold text-red-700 bg-red-100 rounded-full">
                            {{ $patient->blood_type }}
                        </span>
                    </div>
                    @if ($patient->phone)
                        <div>
                            <p class="text-gray-500 text-xs font-medium mb-1">Telepon / WhatsApp</p>
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $patient->phone) }}"
                                target="_blank"
                                class="inline-flex items-center gap-2 text-green-600 hover:text-green-700 font-medium">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z" />
                                </svg>
                                {{ $patient->phone }}
                            </a>
                        </div>
                    @endif
                    <div>
                        <p class="text-gray-500 text-xs font-medium mb-1">Alamat Lengkap</p>
                        <p class="text-gray-900">{{ $patient->address }}</p>
                    </div>
                </div>
            </div>

            <!-- Husband Info -->
            @if ($patient->husband_name)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="font-bold text-gray-900 mb-4 flex items-center text-lg">
                        <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        Data Suami
                    </h3>
                    <div class="space-y-4 text-sm">
                        <div>
                            <p class="text-gray-500 text-xs font-medium mb-1">Nama Lengkap</p>
                            <p class="font-semibold text-gray-900">{{ $patient->husband_name }}</p>
                        </div>
                        @if ($patient->husband_nik)
                            <div>
                                <p class="text-gray-500 text-xs font-medium mb-1">NIK</p>
                                <p class="font-mono text-gray-900">{{ $patient->husband_nik }}</p>
                            </div>
                        @endif
                        @if ($patient->husband_job)
                            <div>
                                <p class="text-gray-500 text-xs font-medium mb-1">Pekerjaan</p>
                                <p class="text-gray-900">{{ $patient->husband_job }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        <!-- Right Column - Pregnancy & Visits -->
        <div class="lg:col-span-2 space-y-6">
            @if ($patient->activePregnancy)
                <!-- Pregnancy Card (Active or Delivered) -->
                <div
                    class="bg-white rounded-xl shadow-sm border-2 {{ $patient->activePregnancy->status === 'Aktif' ? 'border-green-500' : 'border-blue-500' }} overflow-hidden">
                    <div
                        class="bg-gradient-to-r {{ $patient->activePregnancy->status === 'Aktif' ? 'from-green-500 to-green-600' : 'from-blue-500 to-blue-600' }} p-6 text-white">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="font-bold text-xl flex items-center gap-2">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path>
                                    <path fill-rule="evenodd"
                                        d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm9.707 5.707a1 1 0 00-1.414-1.414L9 12.586l-1.293-1.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                {{ $patient->activePregnancy->status === 'Aktif' ? 'Kehamilan Aktif' : 'Perawatan Nifas' }}
                            </h3>
                            <span
                                class="px-3 py-1 text-sm font-semibold bg-white {{ $patient->activePregnancy->status === 'Aktif' ? 'text-green-700' : 'text-blue-700' }} rounded-full">
                                {{ $patient->activePregnancy->status === 'Aktif' ? 'Aktif' : 'Lahir' }}
                            </span>
                        </div>

                        <!-- Quick Action Buttons -->
                        <div class="flex gap-2 flex-wrap">
                            @if (in_array($patient->activePregnancy->status, ['Aktif']))
                                <a href="{{ route('pregnancies.delivery', $patient->activePregnancy->id) }}"
                                    class="inline-flex items-center gap-2 px-4 py-2 bg-white text-green-700 font-semibold rounded-lg hover:bg-green-50 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                        </path>
                                    </svg>
                                    Catat Persalinan
                                </a>
                            @endif

                            @if ($patient->activePregnancy->status === 'Lahir' && $patient->activePregnancy->delivery_date)
                                <a href="{{ route('pregnancies.postnatal', $patient->activePregnancy->id) }}"
                                    class="inline-flex items-center gap-2 px-4 py-2 bg-white text-blue-700 font-semibold rounded-lg hover:bg-blue-50 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                                        </path>
                                    </svg>
                                    Kunjungan Nifas
                                </a>
                            @endif
                        </div>
                    </div>

                    <div class="p-6">
                        <!-- Key Metrics -->
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                            <div class="text-center p-4 bg-blue-50 rounded-lg">
                                <p class="text-xs text-blue-600 font-medium mb-1">Gravida</p>
                                <p class="text-2xl font-bold text-blue-900">{{ $patient->activePregnancy->gravida }}
                                </p>
                            </div>
                            <div class="text-center p-4 bg-purple-50 rounded-lg">
                                <p class="text-xs text-purple-600 font-medium mb-1">Usia Kehamilan</p>
                                <p class="text-2xl font-bold text-purple-900">
                                    {{ $patient->activePregnancy->gestational_age }}</p>
                                <p class="text-xs text-purple-600">minggu</p>
                            </div>
                            <div class="text-center p-4 bg-green-50 rounded-lg">
                                <p class="text-xs text-green-600 font-medium mb-1">HPHT</p>
                                <p class="text-sm font-bold text-green-900">
                                    {{ $patient->activePregnancy->hpht->format('d/m/Y') }}</p>
                            </div>
                            <div class="text-center p-4 bg-orange-50 rounded-lg">
                                <p class="text-xs text-orange-600 font-medium mb-1">HPL</p>
                                <p class="text-sm font-bold text-orange-900">
                                    {{ $patient->activePregnancy->hpl->format('d/m/Y') }}</p>
                            </div>
                        </div>

                        @if ($patient->activePregnancy->status === 'Lahir' && $patient->activePregnancy->delivery_date)
                            <!-- Delivery Information -->
                            <div class="mb-6 p-5 bg-blue-50 border-2 border-blue-300 rounded-lg">
                                <h4 class="font-bold text-blue-900 flex items-center gap-2 mb-4">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                                        </path>
                                    </svg>
                                    Informasi Persalinan
                                </h4>
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                    <div class="text-center">
                                        <p class="text-xs text-blue-600 font-medium mb-1">Tanggal Lahir</p>
                                        <p class="text-sm font-bold text-blue-900">
                                            {{ $patient->activePregnancy->delivery_date->format('d/m/Y H:i') }}</p>
                                    </div>
                                    <div class="text-center">
                                        <p class="text-xs text-blue-600 font-medium mb-1">Cara Persalinan</p>
                                        <p class="text-sm font-bold text-blue-900">
                                            {{ $patient->activePregnancy->delivery_method }}</p>
                                    </div>
                                    <div class="text-center">
                                        <p class="text-xs text-blue-600 font-medium mb-1">Penolong</p>
                                        <p class="text-sm font-bold text-blue-900">
                                            {{ $patient->activePregnancy->birth_attendant }}</p>
                                    </div>
                                    <div class="text-center">
                                        <p class="text-xs text-blue-600 font-medium mb-1">Kondisi Bayi</p>
                                        <p class="text-sm font-bold text-blue-900">
                                            {{ $patient->activePregnancy->outcome }}</p>
                                    </div>
                                </div>
                                @if ($patient->activePregnancy->baby_gender)
                                    <div class="mt-3 text-center">
                                        <p class="text-xs text-blue-600 font-medium mb-1">Jenis Kelamin Bayi</p>
                                        <p class="text-sm font-bold text-blue-900">
                                            {{ $patient->activePregnancy->baby_gender === 'L' ? 'Laki-laki' : 'Perempuan' }}
                                        </p>
                                    </div>
                                @endif
                            </div>
                        @endif

                        @php
                            $latestVisit = $patient->activePregnancy->ancVisits->sortByDesc('visit_date')->first();
                        @endphp

                        @if ($latestVisit)
                            <!-- Risk Summary -->
                            <div
                                class="mb-6 p-5 rounded-lg {{ $latestVisit->risk_category === 'Ekstrem' ? 'bg-red-50 border-2 border-red-300' : ($latestVisit->risk_category === 'Tinggi' ? 'bg-orange-50 border-2 border-orange-300' : 'bg-green-50 border-2 border-green-300') }}">
                                <div class="flex items-center justify-between mb-4">
                                    <h4 class="font-bold text-gray-900 flex items-center gap-2">
                                        <svg class="w-5 h-5 {{ $latestVisit->risk_category === 'Ekstrem' ? 'text-red-600' : ($latestVisit->risk_category === 'Tinggi' ? 'text-orange-600' : 'text-green-600') }}"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Status Risiko Terkini
                                    </h4>
                                    <span
                                        class="px-4 py-2 text-sm font-bold rounded-full {{ $latestVisit->risk_category === 'Ekstrem' ? 'bg-red-600 text-white' : ($latestVisit->risk_category === 'Tinggi' ? 'bg-orange-500 text-white' : 'bg-green-500 text-white') }}">
                                        {{ $latestVisit->risk_category }}
                                    </span>
                                </div>

                                <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                                    <div class="bg-white p-3 rounded-lg">
                                        <p class="text-xs text-gray-600 mb-1">MAP Score</p>
                                        <p
                                            class="text-lg font-bold {{ $latestVisit->map_score > 100 ? 'text-red-600' : ($latestVisit->map_score > 90 ? 'text-orange-600' : 'text-green-600') }}">
                                            {{ number_format($latestVisit->map_score, 1) }}
                                        </p>
                                    </div>
                                    <div class="bg-white p-3 rounded-lg">
                                        <p class="text-xs text-gray-600 mb-1">Hemoglobin</p>
                                        <p
                                            class="text-lg font-bold {{ $latestVisit->hb && $latestVisit->hb < 11 ? 'text-red-600' : 'text-green-600' }}">
                                            {{ $latestVisit->hb ? number_format($latestVisit->hb, 1) . ' g/dL' : '-' }}
                                        </p>
                                    </div>
                                    <div class="bg-white p-3 rounded-lg">
                                        <p class="text-xs text-gray-600 mb-1">LILA</p>
                                        <p
                                            class="text-lg font-bold {{ $latestVisit->lila && $latestVisit->lila < 23.5 ? 'text-red-600' : 'text-green-600' }}">
                                            {{ $latestVisit->lila ? number_format($latestVisit->lila, 1) . ' cm' : '-' }}
                                        </p>
                                    </div>
                                    <div class="bg-white p-3 rounded-lg">
                                        <p class="text-xs text-gray-600 mb-1">HIV</p>
                                        <span
                                            class="text-sm font-bold {{ $latestVisit->hiv_status === 'R' ? 'text-red-600' : 'text-green-600' }}">
                                            {{ $latestVisit->hiv_status === 'R' ? 'Reaktif' : 'Non-Reaktif' }}
                                        </span>
                                    </div>
                                    <div class="bg-white p-3 rounded-lg">
                                        <p class="text-xs text-gray-600 mb-1">Syphilis</p>
                                        <span
                                            class="text-sm font-bold {{ $latestVisit->syphilis_status === 'R' ? 'text-red-600' : 'text-green-600' }}">
                                            {{ $latestVisit->syphilis_status === 'R' ? 'Reaktif' : 'Non-Reaktif' }}
                                        </span>
                                    </div>
                                    <div class="bg-white p-3 rounded-lg">
                                        <p class="text-xs text-gray-600 mb-1">HBsAg</p>
                                        <span
                                            class="text-sm font-bold {{ $latestVisit->hbsag_status === 'R' ? 'text-red-600' : 'text-green-600' }}">
                                            {{ $latestVisit->hbsag_status === 'R' ? 'Reaktif' : 'Non-Reaktif' }}
                                        </span>
                                    </div>
                                </div>
                                <p class="text-xs text-gray-600 mt-3">Kunjungan terakhir:
                                    {{ $latestVisit->visit_date->locale('id')->isoFormat('D MMMM YYYY') }}</p>
                            </div>
                        @endif

                        <!-- ANC Visit History -->
                        <div>
                            <div class="flex items-center justify-between mb-4">
                                <h4 class="font-bold text-gray-900 flex items-center gap-2">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
                                        </path>
                                    </svg>
                                    Riwayat Kunjungan ANC
                                </h4>
                                <span
                                    class="text-sm text-gray-500">{{ $patient->activePregnancy->ancVisits->count() }}
                                    kunjungan</span>
                            </div>

                            @if ($patient->activePregnancy->ancVisits->count() > 0)
                                <div class="space-y-3">
                                    @foreach ($patient->activePregnancy->ancVisits->sortByDesc('visit_date') as $visit)
                                        <div
                                            class="flex items-center gap-4 p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors border border-gray-200">
                                            <div
                                                class="w-14 h-14 flex-shrink-0 rounded-lg flex items-center justify-center text-white font-bold text-lg
                                                {{ in_array($visit->visit_code, ['K1', 'K2']) ? 'bg-blue-500' : '' }}
                                                {{ in_array($visit->visit_code, ['K3', 'K4']) ? 'bg-green-500' : '' }}
                                                {{ in_array($visit->visit_code, ['K5', 'K6']) ? 'bg-purple-500' : '' }}">
                                                {{ $visit->visit_code }}
                                            </div>
                                            <div class="flex-1">
                                                <p class="font-semibold text-gray-900">
                                                    {{ $visit->visit_date->locale('id')->isoFormat('D MMMM YYYY') }}
                                                </p>
                                                <p class="text-sm text-gray-600">UK: {{ $visit->gestational_age }}
                                                    minggu • MAP: {{ number_format($visit->map_score, 1) }}</p>
                                            </div>
                                            <span
                                                class="px-3 py-1 text-xs font-semibold rounded-full
                                                {{ $visit->risk_category === 'Ekstrem' ? 'bg-red-100 text-red-800' : '' }}
                                                {{ $visit->risk_category === 'Tinggi' ? 'bg-orange-100 text-orange-800' : '' }}
                                                {{ $visit->risk_category === 'Rendah' ? 'bg-green-100 text-green-800' : '' }}">
                                                {{ $visit->risk_category }}
                                            </span>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-8 bg-gray-50 rounded-lg">
                                    <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                        </path>
                                    </svg>
                                    <p class="text-gray-500 font-medium mb-2">Belum ada kunjungan ANC</p>
                                    <a href="{{ route('anc-visits.create', $patient->activePregnancy->id) }}"
                                        class="text-sm text-blue-600 hover:text-blue-700 font-medium">
                                        Tambah kunjungan pertama →
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @else
                <!-- No Active Pregnancy -->
                <div class="bg-white rounded-xl shadow-sm border-2 border-dashed border-gray-300 p-12 text-center">
                    <svg class="w-20 h-20 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                    </svg>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Tidak Ada Kehamilan Aktif</h3>
                    <p class="text-gray-600 mb-6">Pasien saat ini tidak sedang dalam masa kehamilan</p>
                    <a href="{{ route('pregnancies.create', $patient->id) }}"
                        class="inline-flex items-center gap-2 px-6 py-3 bg-purple-600 hover:bg-purple-700 text-white font-semibold rounded-lg transition-colors shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Daftarkan Kehamilan Baru
                    </a>
                </div>
            @endif

            <!-- All Pregnancies History -->
            @if ($patient->pregnancies->count() > 0)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2 text-lg">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Riwayat Semua Kehamilan
                    </h3>
                    <div class="space-y-3">
                        @foreach ($patient->pregnancies->sortByDesc('created_at') as $pregnancy)
                            <div
                                class="p-4 rounded-lg border-2 {{ $pregnancy->status === 'Aktif' ? 'border-green-300 bg-green-50' : 'border-gray-200 bg-gray-50' }}">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="font-semibold text-gray-900">{{ $pregnancy->gravida }}</span>
                                    <span
                                        class="px-3 py-1 text-xs font-semibold rounded-full {{ $pregnancy->status === 'Aktif' ? 'bg-green-600 text-white' : 'bg-gray-600 text-white' }}">
                                        {{ $pregnancy->status }}
                                    </span>
                                </div>
                                <div class="text-sm text-gray-600">
                                    <p>HPHT: {{ $pregnancy->hpht->format('d/m/Y') }} • HPL:
                                        {{ $pregnancy->hpl->format('d/m/Y') }}</p>
                                    @if ($pregnancy->ancVisits->count() > 0)
                                        <p class="mt-1">{{ $pregnancy->ancVisits->count() }} kunjungan ANC tercatat
                                        </p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-dashboard-layout>
