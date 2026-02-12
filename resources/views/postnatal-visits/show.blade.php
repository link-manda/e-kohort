<x-dashboard-layout>
<div class="container mx-auto px-4 py-6 max-w-5xl">
    {{-- Header & Breadcrumb --}}
    <div class="mb-6">
        <div class="flex items-center gap-2 text-sm text-gray-600 mb-3">
            <a href="{{ route('dashboard') }}" class="hover:text-blue-600">Dashboard</a>
            <x-heroicon-m-chevron-right class="w-4 h-4" />
            <a href="{{ route('patients.show', $patient->id) }}" class="hover:text-blue-600">{{ $patient->name }}</a>
            <x-heroicon-m-chevron-right class="w-4 h-4" />
            <span class="text-gray-900 font-medium">Detail Kunjungan Nifas</span>
        </div>

        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Detail Kunjungan Nifas</h1>
                <p class="text-gray-600 mt-1">Rekam medis kunjungan masa nifas</p>
            </div>
            <a href="{{ route('patients.show', $patient->id) }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg transition-colors">
                <x-heroicon-m-arrow-left class="w-4 h-4" />
                Kembali
            </a>
        </div>
    </div>

    {{-- Patient & Delivery Info Summary --}}
    <div class="bg-gradient-to-r from-pink-50 to-purple-50 rounded-xl p-4 mb-6 border border-pink-200">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <p class="text-xs text-gray-600 mb-1">Nama Pasien</p>
                <p class="font-bold text-gray-900">{{ $patient->name }}</p>
                <p class="text-xs text-gray-500">No. RM: {{ $patient->no_rm }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-600 mb-1">Data Persalinan</p>
                @php
                    $deliveryDateDisplay = null;
                    if ($delivery && $delivery->delivery_date) {
                        $deliveryDateDisplay = $delivery->delivery_date;
                    } elseif ($visit->pregnancy->delivery_date) {
                        $deliveryDateDisplay = $visit->pregnancy->delivery_date;
                    }
                @endphp
                <p class="font-semibold text-gray-900">{{ $deliveryDateDisplay ? $deliveryDateDisplay->format('d M Y') : '-' }}</p>
                <p class="text-xs text-gray-500">G{{ $visit->pregnancy->gravida }}P{{ $visit->pregnancy->para }}A{{ $visit->pregnancy->abortus }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-600 mb-1">Data Bayi</p>
                @if($delivery && $delivery->baby_name)
                    <p class="font-semibold text-gray-900">{{ $delivery->baby_name }}</p>
                    <p class="text-xs text-gray-500">{{ $delivery->baby_gender === 'Laki-laki' ? '👦' : '👧' }} {{ $delivery->baby_gender }}</p>
                @else
                    <p class="text-xs text-gray-500">-</p>
                @endif
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
                    <x-heroicon-o-calendar class="w-5 h-5 text-purple-600" />
                    <h3 class="font-bold text-gray-900">Informasi Kunjungan</h3>
                </div>
                <div class="p-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-xs text-gray-500 mb-1">Kode Kunjungan</p>
                            @php
                                $kfColors = [
                                    'KF1' => 'bg-blue-100 text-blue-800',
                                    'KF2' => 'bg-green-100 text-green-800',
                                    'KF3' => 'bg-purple-100 text-purple-800',
                                    'KF4' => 'bg-orange-100 text-orange-800',
                                ];
                                $colorClass = $kfColors[$visit->visit_code] ?? 'bg-gray-100 text-gray-800';
                            @endphp
                            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-bold {{ $colorClass }}">
                                {{ $visit->visit_code_label }}
                            </span>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 mb-1">Hari Postpartum</p>
                            <div class="inline-flex items-center gap-2 px-3 py-1.5 bg-pink-100 rounded-lg">
                                <x-heroicon-s-calendar class="w-4 h-4 text-pink-600" />
                                <span class="text-sm font-bold text-pink-800">Hari ke-{{ $visit->days_post_partum }}</span>
                            </div>
                        </div>
                        <div class="col-span-2">
                            <p class="text-xs text-gray-500 mb-1">Tanggal Kunjungan</p>
                            <p class="font-semibold text-gray-900">{{ $visit->visit_date->format('d M Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Section 2: Pemeriksaan Fisik --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-red-50 px-4 py-3 border-b flex items-center gap-2">
                    <x-heroicon-o-heart class="w-5 h-5 text-red-500" />
                    <h3 class="font-bold text-gray-900">Pemeriksaan Fisik</h3>
                </div>
                <div class="p-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-xs text-gray-500 mb-1">Tekanan Darah</p>
                            @php
                                $isHypertensive = $visit->td_systolic >= 140 || $visit->td_diastolic >= 90;
                            @endphp
                            <div class="flex items-center gap-2">
                                <p class="text-lg font-bold {{ $isHypertensive ? 'text-red-600' : 'text-green-600' }}">
                                    {{ $visit->td_systolic ?? '-' }}/{{ $visit->td_diastolic ?? '-' }}
                                    <span class="text-sm text-gray-500">mmHg</span>
                                </p>
                                @if($isHypertensive)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold bg-red-100 text-red-700">
                                        <x-heroicon-s-exclamation-triangle class="w-3 h-3 mr-1" />
                                        Hipertensi
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 mb-1">Suhu Tubuh</p>
                            @php
                                $hasFever = $visit->temperature > 37.5;
                            @endphp
                            <div class="flex items-center gap-2">
                                <p class="text-lg font-bold {{ $hasFever ? 'text-orange-600' : 'text-blue-600' }}">
                                    {{ $visit->temperature ?? '-' }}
                                    <span class="text-sm text-gray-500">°C</span>
                                </p>
                                @if($hasFever)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold bg-orange-100 text-orange-700">
                                        <x-heroicon-s-exclamation-triangle class="w-3 h-3 mr-1" />
                                        Demam
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Section 3: Pemeriksaan Nifas --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-purple-50 px-4 py-3 border-b flex items-center gap-2">
                    <x-heroicon-o-clipboard-document-check class="w-5 h-5 text-purple-600" />
                    <h3 class="font-bold text-gray-900">Pemeriksaan Nifas</h3>
                </div>
                <div class="p-4 space-y-4">
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Lochea</p>
                        @php
                            $locheaColors = [
                                'Rubra' => 'bg-red-50 text-red-700 border-red-200',
                                'Sanguinolenta' => 'bg-pink-50 text-pink-700 border-pink-200',
                                'Serosa' => 'bg-yellow-50 text-yellow-700 border-yellow-200',
                                'Alba' => 'bg-gray-50 text-gray-700 border-gray-200',
                            ];
                            $locheaClass = $locheaColors[$visit->lochea] ?? 'bg-gray-50 text-gray-700 border-gray-200';
                        @endphp
                        <div class="inline-flex items-center px-3 py-2 rounded-lg border-2 {{ $locheaClass }}">
                            <span class="text-sm font-bold">{{ $visit->lochea ?? 'Tidak Dicatat' }}</span>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">
                            @if($visit->lochea === 'Rubra')
                                Merah segar (1-3 hari postpartum)
                            @elseif($visit->lochea === 'Sanguinolenta')
                                Merah kecoklatan (4-7 hari postpartum)
                            @elseif($visit->lochea === 'Serosa')
                                Kuning kecoklatan (7-14 hari postpartum)
                            @elseif($visit->lochea === 'Alba')
                                Putih kekuningan (>14 hari postpartum)
                            @endif
                        </p>
                    </div>

                    @if($visit->uterine_involution)
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Involusi Uterus</p>
                        <div class="bg-gray-50 p-3 rounded-lg">
                            <p class="text-sm text-gray-900">{{ $visit->uterine_involution }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Section 4: Suplemen & Intervensi --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-green-50 px-4 py-3 border-b flex items-center gap-2">
                    <x-heroicon-o-beaker class="w-5 h-5 text-green-600" />
                    <h3 class="font-bold text-gray-900">Suplemen & Intervensi</h3>
                </div>
                <div class="p-4 space-y-3">
                    {{-- Vitamin A --}}
                    <div class="flex items-center justify-between p-3 rounded-lg {{ $visit->vitamin_a ? 'bg-green-50 border border-green-200' : 'bg-gray-50' }}">
                        <div class="flex items-center gap-3">
                            @if($visit->vitamin_a)
                                <x-heroicon-s-check-circle class="w-6 h-6 text-green-600" />
                            @else
                                <x-heroicon-s-x-circle class="w-6 h-6 text-gray-400" />
                            @endif
                            <div>
                                <p class="text-sm font-bold text-gray-900">Vitamin A</p>
                                <p class="text-xs text-gray-500">Kapsul merah 200.000 IU</p>
                            </div>
                        </div>
                        <span class="text-xs font-semibold {{ $visit->vitamin_a ? 'text-green-700' : 'text-gray-500' }}">
                            {{ $visit->vitamin_a ? 'Sudah Diberikan' : 'Belum' }}
                        </span>
                    </div>

                    {{-- Fe Tablets --}}
                    <div class="flex items-center justify-between p-3 rounded-lg {{ $visit->fe_tablets ? 'bg-blue-50 border border-blue-200' : 'bg-gray-50' }}">
                        <div class="flex items-center gap-3">
                            <x-heroicon-s-beaker class="w-6 h-6 {{ $visit->fe_tablets ? 'text-blue-600' : 'text-gray-400' }}" />
                            <div>
                                <p class="text-sm font-bold text-gray-900">Tablet Fe (Zat Besi)</p>
                                <p class="text-xs text-gray-500">Tablet tambah darah</p>
                            </div>
                        </div>
                        <span class="text-lg font-bold {{ $visit->fe_tablets ? 'text-blue-700' : 'text-gray-500' }}">
                            {{ $visit->fe_tablets ?? 0 }} tablet
                        </span>
                    </div>

                    {{-- Complication Check --}}
                    <div class="flex items-center justify-between p-3 rounded-lg {{ $visit->complication_check ? 'bg-purple-50 border border-purple-200' : 'bg-gray-50' }}">
                        <div class="flex items-center gap-3">
                            @if($visit->complication_check)
                                <x-heroicon-s-check-circle class="w-6 h-6 text-purple-600" />
                            @else
                                <x-heroicon-s-x-circle class="w-6 h-6 text-gray-400" />
                            @endif
                            <div>
                                <p class="text-sm font-bold text-gray-900">Pemeriksaan Komplikasi</p>
                                <p class="text-xs text-gray-500">Deteksi dini komplikasi nifas</p>
                            </div>
                        </div>
                        <span class="text-xs font-semibold {{ $visit->complication_check ? 'text-purple-700' : 'text-gray-500' }}">
                            {{ $visit->complication_check ? 'Sudah Dilakukan' : 'Belum' }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- Section 5: Kesimpulan --}}
            @if($visit->conclusion)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-yellow-50 px-4 py-3 border-b flex items-center gap-2">
                    <x-heroicon-o-document-text class="w-5 h-5 text-yellow-600" />
                    <h3 class="font-bold text-gray-900">Kesimpulan & Tindakan</h3>
                </div>
                <div class="p-4">
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-sm text-gray-800 whitespace-pre-wrap">{{ $visit->conclusion }}</p>
                    </div>
                </div>
            </div>
            @endif

        </div>

        {{-- Right Column: Quick Info & Actions --}}
        <div class="space-y-6">

            {{-- Service Fee --}}
            <div class="bg-green-50 rounded-xl p-4 border border-green-200">
                <div class="flex items-center gap-2 mb-3">
                    <x-heroicon-o-currency-dollar class="w-5 h-5 text-green-600" />
                    <h4 class="font-bold text-green-900">Biaya Layanan</h4>
                </div>
                <p class="text-3xl font-bold text-green-700">Rp {{ number_format($visit->service_fee ?? 0, 0, ',', '.') }}</p>
            </div>

            {{-- Health Status Summary --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
                <h4 class="font-bold text-gray-900 mb-3">Status Kesehatan</h4>
                <div class="space-y-2 text-sm">
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Tekanan Darah</span>
                        <span class="font-semibold {{ $isHypertensive ? 'text-red-600' : 'text-green-600' }}">
                            {{ $isHypertensive ? 'Tinggi' : 'Normal' }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Suhu Tubuh</span>
                        <span class="font-semibold {{ $hasFever ? 'text-orange-600' : 'text-blue-600' }}">
                            {{ $hasFever ? 'Demam' : 'Normal' }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Vitamin A</span>
                        <span class="font-semibold {{ $visit->vitamin_a ? 'text-green-600' : 'text-gray-500' }}">
                            {{ $visit->vitamin_a ? '✓ Sudah' : '✗ Belum' }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Komplikasi</span>
                        <span class="font-semibold {{ $visit->complication_check ? 'text-green-600' : 'text-gray-500' }}">
                            {{ $visit->complication_check ? '✓ Diperiksa' : '✗ Belum' }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 space-y-3">
                <h4 class="font-bold text-gray-900 mb-3">Aksi</h4>

                <a href="{{ route('postnatal-visits.edit', $visit->id) }}"
                   class="w-full flex items-center justify-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-colors">
                    <x-heroicon-m-pencil class="w-4 h-4" />
                    Edit Data
                </a>

                <form action="{{ route('postnatal-visits.destroy', $visit->id) }}" method="POST"
                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus data kunjungan nifas ini?');">
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
</div>
</x-dashboard-layout>
