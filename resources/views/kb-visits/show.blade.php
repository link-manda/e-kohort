<x-dashboard-layout>
<div class="container mx-auto px-4 py-6 max-w-5xl">
    {{-- Header & Breadcrumb --}}
    <div class="mb-6">
        <div class="flex items-center gap-2 text-sm text-gray-600 mb-3">
            <a href="{{ route('dashboard') }}" class="hover:text-blue-600">Dashboard</a>
            <x-heroicon-m-chevron-right class="w-4 h-4" />
            <a href="{{ route('patients.show', $patient->id) }}" class="hover:text-blue-600">{{ $patient->name }}</a>
            <x-heroicon-m-chevron-right class="w-4 h-4" />
            <span class="text-gray-900 font-medium">Detail Kunjungan KB</span>
        </div>

        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Detail Kunjungan KB</h1>
                <p class="text-gray-600 mt-1">Rekam medis kunjungan keluarga berencana</p>
            </div>
            <a href="{{ route('patients.show', $patient->id) }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg transition-colors">
                <x-heroicon-m-arrow-left class="w-4 h-4" />
                Kembali
            </a>
        </div>
    </div>

    {{-- Patient Info Summary --}}
    <div class="bg-gradient-to-r from-blue-50 to-purple-50 rounded-xl p-4 mb-6 border border-blue-100">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-blue-600 flex items-center justify-center text-white font-bold text-xl">
                {{ strtoupper(substr($patient->name, 0, 2)) }}
            </div>
            <div class="flex-1 grid grid-cols-2 md:grid-cols-4 gap-4">
                <div>
                    <p class="text-xs text-gray-600">Nama Pasien</p>
                    <p class="font-semibold text-gray-900">{{ $patient->name }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-600">No. RM</p>
                    <p class="font-semibold text-gray-900">{{ $patient->no_rm }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-600">Tanggal Lahir</p>
                    <p class="font-semibold text-gray-900">{{ $patient->tanggal_lahir?->format('d/m/Y') ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-600">Telepon</p>
                    <p class="font-semibold text-gray-900">{{ $patient->phone ?? '-' }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Content Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Left Column: Detail Information --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Section 1: Informasi Kunjungan --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-gray-50 px-4 py-3 border-b flex items-center gap-2">
                    <x-heroicon-o-calendar class="w-5 h-5 text-blue-600" />
                    <h3 class="font-bold text-gray-900">Informasi Kunjungan</h3>
                </div>
                <div class="p-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-xs text-gray-500 mb-1">Tanggal Kunjungan</p>
                            <p class="font-semibold text-gray-900">{{ $visit->visit_date->format('d M Y, H:i') }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 mb-1">Jenis Kunjungan</p>
                            @if($visit->visit_type == 'Peserta Baru')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                    Peserta Baru
                                </span>
                            @elseif($visit->visit_type == 'Peserta Lama')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                                    Peserta Lama
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-purple-100 text-purple-800">
                                    Ganti Cara
                                </span>
                            @endif
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 mb-1">Jenis Pembayaran</p>
                            <p class="font-semibold text-gray-900">{{ $visit->payment_type }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 mb-1">Petugas Pemeriksa</p>
                            <p class="font-semibold text-gray-900">{{ $visit->midwife_name ?? '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Section 2: Metode Kontrasepsi --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-purple-50 px-4 py-3 border-b flex items-center gap-2">
                    <x-heroicon-o-shield-check class="w-5 h-5 text-purple-600" />
                    <h3 class="font-bold text-gray-900">Metode Kontrasepsi</h3>
                </div>
                <div class="p-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="col-span-2">
                            <p class="text-xs text-gray-500 mb-1">Metode KB</p>
                            <p class="text-lg font-bold text-purple-700">{{ $visit->kbMethod->name ?? '-' }}</p>
                            <p class="text-xs text-gray-500 mt-1">Kategori: {{ $visit->kbMethod->category ?? '-' }}</p>
                        </div>
                        @if($visit->contraception_brand)
                        <div class="col-span-2">
                            <p class="text-xs text-gray-500 mb-1">Brand/Merk</p>
                            <p class="font-semibold text-gray-900">{{ $visit->contraception_brand }}</p>
                        </div>
                        @endif
                        <div class="col-span-2">
                            <p class="text-xs text-gray-500 mb-1">Informed Consent</p>
                            @if($visit->informed_consent)
                                <div class="inline-flex items-center gap-2 px-3 py-2 bg-green-50 rounded-lg border border-green-200">
                                    <x-heroicon-s-check-circle class="w-5 h-5 text-green-600" />
                                    <span class="text-sm font-medium text-green-800">Sudah Ditandatangani</span>
                                </div>
                            @else
                                <div class="inline-flex items-center gap-2 px-3 py-2 bg-gray-50 rounded-lg border border-gray-200">
                                    <x-heroicon-s-x-circle class="w-5 h-5 text-gray-400" />
                                    <span class="text-sm font-medium text-gray-600">Tidak Diperlukan</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Section 3: Pemeriksaan Fisik --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-blue-50 px-4 py-3 border-b flex items-center gap-2">
                    <x-heroicon-o-heart class="w-5 h-5 text-red-500" />
                    <h3 class="font-bold text-gray-900">Pemeriksaan Fisik</h3>
                </div>
                <div class="p-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-xs text-gray-500 mb-1">Berat Badan</p>
                            <p class="text-lg font-bold text-blue-600">{{ $visit->weight ?? '-' }} <span class="text-sm text-gray-500">kg</span></p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 mb-1">Tekanan Darah</p>
                            <div class="flex items-center gap-2">
                                <p class="text-lg font-bold {{ $visit->isHypertensive() ? 'text-red-600' : 'text-green-600' }}">
                                    {{ $visit->blood_pressure_systolic ?? '-' }}/{{ $visit->blood_pressure_diastolic ?? '-' }}
                                    <span class="text-sm text-gray-500">mmHg</span>
                                </p>
                                @if($visit->isHypertensive())
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold bg-red-100 text-red-700">
                                        <x-heroicon-s-exclamation-triangle class="w-3 h-3 mr-1" />
                                        Hipertensi
                                    </span>
                                @endif
                            </div>
                        </div>
                        @if($visit->physical_exam_notes)
                        <div class="col-span-2">
                            <p class="text-xs text-gray-500 mb-1">Catatan Pemeriksaan</p>
                            <p class="text-sm text-gray-700 bg-gray-50 p-3 rounded-lg">{{ $visit->physical_exam_notes }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Section 4: Diagnosis & Keluhan --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-yellow-50 px-4 py-3 border-b flex items-center gap-2">
                    <x-heroicon-o-clipboard-document-list class="w-5 h-5 text-yellow-600" />
                    <h3 class="font-bold text-gray-900">Diagnosis & Keluhan</h3>
                </div>
                <div class="p-4 space-y-4">
                    @if($visit->icd_code || $visit->diagnosis)
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Diagnosis</p>
                        <div class="bg-gray-50 p-3 rounded-lg">
                            @if($visit->icd_code)
                                <p class="text-xs text-gray-600 mb-1">Kode ICD-10: <span class="font-mono font-semibold">{{ $visit->icd_code }}</span></p>
                            @endif
                            <p class="text-sm text-gray-900">{{ $visit->diagnosis ?? '-' }}</p>
                        </div>
                    </div>
                    @endif

                    @if($visit->side_effects)
                    <div class="bg-orange-50 border border-orange-200 rounded-lg p-4">
                        <div class="flex items-start gap-3">
                            <x-heroicon-s-exclamation-triangle class="w-5 h-5 text-orange-600 flex-shrink-0 mt-0.5" />
                            <div>
                                <p class="text-sm font-bold text-orange-900 mb-1">Efek Samping</p>
                                <p class="text-sm text-orange-800">{{ $visit->side_effects }}</p>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($visit->complications)
                    <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                        <div class="flex items-start gap-3">
                            <x-heroicon-s-exclamation-circle class="w-5 h-5 text-red-600 flex-shrink-0 mt-0.5" />
                            <div>
                                <p class="text-sm font-bold text-red-900 mb-1">Komplikasi</p>
                                <p class="text-sm text-red-800">{{ $visit->complications }}</p>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if(!$visit->icd_code && !$visit->diagnosis && !$visit->side_effects && !$visit->complications)
                    <p class="text-center text-gray-500 text-sm py-4">Tidak ada keluhan atau komplikasi yang tercatat</p>
                    @endif
                </div>
            </div>

        </div>

        {{-- Right Column: Quick Info & Actions --}}
        <div class="space-y-6">

            {{-- Next Visit Schedule --}}
            @if($visit->next_visit_date)
            <div class="bg-purple-50 rounded-xl p-4 border border-purple-200">
                <div class="flex items-center gap-2 mb-3">
                    <x-heroicon-o-calendar-days class="w-5 h-5 text-purple-600" />
                    <h4 class="font-bold text-purple-900">Kunjungan Berikutnya</h4>
                </div>
                @php
                    $daysUntil = now()->diffInDays($visit->next_visit_date, false);
                    $isOverdue = $daysUntil < 0;
                @endphp
                <p class="text-2xl font-bold text-purple-700">{{ $visit->next_visit_date->format('d M Y') }}</p>
                <p class="text-sm mt-1 {{ $isOverdue ? 'text-red-600 font-semibold' : 'text-purple-600' }}">
                    @if($isOverdue)
                        ⚠️ Terlewat {{ abs($daysUntil) }} hari
                    @elseif($daysUntil == 0)
                        📅 HARI INI
                    @else
                        {{ $daysUntil }} hari lagi
                    @endif
                </p>
            </div>
            @endif

            {{-- Service Fee --}}
            <div class="bg-green-50 rounded-xl p-4 border border-green-200">
                <div class="flex items-center gap-2 mb-3">
                    <x-heroicon-o-currency-dollar class="w-5 h-5 text-green-600" />
                    <h4 class="font-bold text-green-900">Biaya Layanan</h4>
                </div>
                <p class="text-3xl font-bold text-green-700">Rp {{ number_format($visit->service_fee ?? 0, 0, ',', '.') }}</p>
            </div>

            {{-- Action Buttons --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 space-y-3">
                <h4 class="font-bold text-gray-900 mb-3">Aksi</h4>

                <a href="{{ route('kb-visits.edit', $visit->id) }}"
                   class="w-full flex items-center justify-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-colors">
                    <x-heroicon-m-pencil class="w-4 h-4" />
                    Edit Data
                </a>

                <form action="{{ route('kb-visits.destroy', $visit->id) }}" method="POST"
                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus data kunjungan KB ini?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="w-full flex items-center justify-center gap-2 px-4 py-2.5 bg-red-100 hover:bg-red-200 text-red-700 font-semibold rounded-lg transition-colors">
                        <x-heroicon-m-trash class="w-4 h-4" />
                        Hapus Data
                    </button>
                </form>

                <a href="{{ route('patients.show', $patient->id) }}"
                   class="w-full flex items-center justify-center gap-2 px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-lg transition-colors">
                    <x-heroicon-m-arrow-left class="w-4 h-4" />
                    Kembali ke Detail Pasien
                </a>
            </div>

            {{-- Metadata --}}
            <div class="bg-gray-50 rounded-lg p-4 text-xs text-gray-600">
                <p class="mb-1"><span class="font-semibold">Dibuat:</span> {{ $visit->created_at->format('d/m/Y H:i') }}</p>
                @if($visit->updated_at != $visit->created_at)
                <p><span class="font-semibold">Diperbarui:</span> {{ $visit->updated_at->format('d/m/Y H:i') }}</p>
                @endif
            </div>

        </div>
    </div>
</x-dashboard-layout>
