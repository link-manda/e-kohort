<div>
    <div x-data x-show="{{ json_encode(false) }}">
        {{-- placeholder for Alpine/Livewire hydration --}}
    </div>

    @if ($open)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <!-- Backdrop -->
            <div class="absolute inset-0 bg-black opacity-50"></div>

            <!-- Modal Content - ENHANCED -->
            <div class="relative bg-white rounded-xl shadow-2xl max-w-3xl w-full max-h-[90vh] overflow-auto z-10">
                {{-- Header --}}
                <div class="px-6 py-4 border-b flex justify-between items-center sticky top-0 bg-white z-20">
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">Preview Pasien KB</h3>
                        <p class="text-sm text-gray-600">Ringkasan status dan riwayat KB pasien</p>
                    </div>
                    <button wire:click="close" class="px-4 py-2 rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium transition-colors">
                        Tutup
                    </button>
                </div>

                <div class="p-6 space-y-6">
                    @if ($patient)
                        {{-- Section 1: Identitas Pasien --}}
                        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-4 border border-blue-200">
                            <div class="flex items-center gap-2 mb-3">
                                <x-heroicon-s-user class="w-5 h-5 text-blue-600" />
                                <h4 class="font-bold text-gray-900">Identitas Pasien</h4>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <p class="text-xs text-gray-600 mb-1">Nama Lengkap</p>
                                    <p class="font-bold text-gray-900">{{ $patient->name }}</p>
                                    <div class="flex items-center gap-4 mt-2">
                                        <div>
                                            <p class="text-xs text-gray-600">No. RM</p>
                                            <p class="text-sm font-medium text-gray-800">{{ $patient->no_rm }}</p>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-600">NIK</p>
                                            <p class="text-sm font-medium text-gray-800">{{ $patient->nik ?? '-' }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-600 mb-1">Kontak</p>
                                    <div class="flex items-center gap-2 mb-2">
                                        <x-heroicon-s-phone class="w-4 h-4 text-gray-500" />
                                        <p class="text-sm font-medium">{{ $patient->phone ?? '-' }}</p>
                                    </div>
                                    <p class="text-xs text-gray-600 mb-1">Alamat</p>
                                    <p class="text-sm text-gray-700">{{ $patient->address ?? '-' }}</p>
                                </div>
                            </div>
                        </div>

                        @if(isset($patient->kb_status))
                            {{-- Section 2: Status KB Aktif --}}
                            <div class="bg-white rounded-xl border-2 {{ $patient->kb_status->is_active ? 'border-green-300' : 'border-gray-300' }} p-4">
                                <div class="flex items-center gap-2 mb-3">
                                    <x-heroicon-s-heart class="w-5 h-5 text-pink-600" />
                                    <h4 class="font-bold text-gray-900">Status KB</h4>
                                </div>
                                <div class="flex items-center justify-between mb-3">
                                    <div class="flex items-center gap-3">
                                        @if($patient->kb_status->is_active)
                                            <span class="flex items-center gap-2 px-3 py-1.5 bg-green-100 text-green-800 rounded-full text-sm font-bold">
                                                <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
                                                AKTIF
                                            </span>
                                        @else
                                            <span class="flex items-center gap-2 px-3 py-1.5 bg-gray-100 text-gray-700 rounded-full text-sm font-bold">
                                                <span class="w-2 h-2 bg-gray-400 rounded-full"></span>
                                                TIDAK AKTIF
                                            </span>
                                        @endif
                                        <div>
                                            <p class="font-bold text-gray-900">{{ $patient->kb_status->current_method }}</p>
                                            @if($patient->kb_status->method_brand)
                                                <p class="text-xs text-gray-600">{{ $patient->kb_status->method_brand }}</p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-xs text-gray-600">Total Kunjungan</p>
                                        <p class="text-2xl font-bold text-blue-600">{{ $patient->kb_status->visit_count }}x</p>
                                    </div>
                                </div>
                                <div class="bg-gray-50 rounded-lg p-3">
                                    <p class="text-xs text-gray-600 mb-1">Mulai Menggunakan</p>
                                    <p class="text-sm font-medium text-gray-900">
                                        {{ $patient->kb_status->start_date->format('d M Y') }}
                                        <span class="text-gray-500">({{ $patient->kb_status->days_since_start }} hari yang lalu)</span>
                                    </p>
                                </div>
                            </div>

                            {{-- Section 3: Jadwal Kunjungan --}}
                            <div class="bg-white rounded-xl border-2
                                {{ $patient->kb_status->alert_level === 'overdue' ? 'border-red-300 bg-red-50' : '' }}
                                {{ $patient->kb_status->alert_level === 'upcoming' ? 'border-orange-300 bg-orange-50' : '' }}
                                {{ $patient->kb_status->alert_level === 'normal' ? 'border-green-300' : '' }}
                                p-4">
                                <div class="flex items-center gap-2 mb-3">
                                    <x-heroicon-s-calendar class="w-5 h-5 text-purple-600" />
                                    <h4 class="font-bold text-gray-900">Jadwal Kunjungan</h4>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <p class="text-xs text-gray-600 mb-1">Kunjungan Terakhir</p>
                                        <p class="font-semibold text-gray-900">{{ $patient->kb_status->last_visit_date->format('d M Y') }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-600 mb-1">Kontrol Berikutnya</p>
                                        @if($patient->kb_status->next_visit_date)
                                            <p class="font-semibold text-gray-900">{{ $patient->kb_status->next_visit_date->format('d M Y') }}</p>
                                            @if($patient->kb_status->is_overdue)
                                                <div class="flex items-center gap-1 mt-1">
                                                    <x-heroicon-s-exclamation-triangle class="w-4 h-4 text-red-600" />
                                                    <span class="text-xs font-bold text-red-700">
                                                        Terlewat {{ abs($patient->kb_status->days_until_next) }} hari
                                                    </span>
                                                </div>
                                            @elseif($patient->kb_status->alert_level === 'upcoming')
                                                <div class="flex items-center gap-1 mt-1">
                                                    <x-heroicon-s-bell class="w-4 h-4 text-orange-600" />
                                                    <span class="text-xs font-bold text-orange-700">
                                                        {{ $patient->kb_status->days_until_next }} hari lagi
                                                    </span>
                                                </div>
                                            @else
                                                <p class="text-xs text-green-700 font-medium mt-1">
                                                    ✓ {{ $patient->kb_status->days_until_next }} hari lagi
                                                </p>
                                            @endif
                                        @else
                                            <p class="text-sm text-gray-500">Belum dijadwalkan</p>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            {{-- Section 4: Status Kesehatan --}}
                            @if($patient->kb_status->has_health_alerts)
                            <div class="bg-yellow-50 rounded-xl border-2 border-yellow-300 p-4">
                                <div class="flex items-center gap-2 mb-3">
                                    <x-heroicon-s-exclamation-triangle class="w-5 h-5 text-yellow-600" />
                                    <h4 class="font-bold text-gray-900">Peringatan Kesehatan</h4>
                                </div>
                                <div class="space-y-2">
                                    @if($patient->kb_status->has_hypertension)
                                        <div class="flex items-center gap-2 p-2 bg-red-50 rounded-lg border border-red-200">
                                            <x-heroicon-s-heart class="w-5 h-5 text-red-600" />
                                            <div>
                                                <p class="text-sm font-bold text-red-800">Hipertensi Terdeteksi</p>
                                                <p class="text-xs text-red-700">
                                                    BP: {{ $patient->kb_status->bp_systolic }}/{{ $patient->kb_status->bp_diastolic }} mmHg
                                                </p>
                                            </div>
                                        </div>
                                    @endif
                                    @if($patient->kb_status->side_effects)
                                        <div class="flex items-center gap-2 p-2 bg-orange-50 rounded-lg border border-orange-200">
                                            <x-heroicon-s-exclamation-circle class="w-5 h-5 text-orange-600" />
                                            <div>
                                                <p class="text-sm font-bold text-orange-800">Efek Samping</p>
                                                <p class="text-xs text-orange-700">{{ $patient->kb_status->side_effects }}</p>
                                            </div>
                                        </div>
                                    @endif
                                    @if($patient->kb_status->complications)
                                        <div class="flex items-center gap-2 p-2 bg-red-50 rounded-lg border border-red-200">
                                            <x-heroicon-s-shield-exclamation class="w-5 h-5 text-red-600" />
                                            <div>
                                                <p class="text-sm font-bold text-red-800">Komplikasi</p>
                                                <p class="text-xs text-red-700">{{ $patient->kb_status->complications }}</p>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            @else
                            <div class="bg-white rounded-xl border-2 border-gray-200 p-4">
                                <div class="flex items-center gap-2 mb-2">
                                    <x-heroicon-s-heart class="w-5 h-5 text-green-600" />
                                    <h4 class="font-bold text-gray-900">Status Kesehatan</h4>
                                </div>
                                <div class="grid grid-cols-2 gap-3 text-sm">
                                    <div class="flex items-center justify-between">
                                        <span class="text-gray-600">Tekanan Darah</span>
                                        <span class="font-semibold {{ $patient->kb_status->has_hypertension ? 'text-red-600' : 'text-green-600' }}">
                                            {{ $patient->kb_status->bp_systolic ?? '-' }}/{{ $patient->kb_status->bp_diastolic ?? '-' }}
                                        </span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span class="text-gray-600">Berat Badan</span>
                                        <span class="font-semibold text-gray-900">{{ $patient->kb_status->weight ?? '-' }} kg</span>
                                    </div>
                                </div>
                            </div>
                            @endif

                            {{-- Section 5: Biaya Terakhir --}}
                            @if($patient->kb_status->service_fee)
                            <div class="bg-green-50 rounded-xl border-2 border-green-300 p-4">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <x-heroicon-s-currency-dollar class="w-5 h-5 text-green-600" />
                                        <h4 class="font-bold text-gray-900">Biaya Layanan Terakhir</h4>
                                    </div>
                                    <p class="text-2xl font-bold text-green-700">
                                        Rp {{ number_format($patient->kb_status->service_fee, 0, ',', '.') }}
                                    </p>
                                </div>
                            </div>
                            @endif
                        @endif

                        {{-- Section 6: Riwayat Kunjungan Singkat --}}
                        <div class="bg-white rounded-xl border-2 border-gray-200 p-4">
                            <h4 class="font-semibold text-gray-900 mb-3">Riwayat Kunjungan Terakhir</h4>
                            <div class="space-y-2">
                                @forelse($patient->kbVisits->take(3) as $visit)
                                    <div class="p-3 border rounded-lg hover:bg-gray-50 transition-colors">
                                        <div class="flex justify-between items-center">
                                            <div>
                                                <p class="text-sm font-medium text-gray-900">
                                                    {{ optional($visit->visit_date)->format('d/m/Y') }} — {{ $visit->kbMethod->name ?? '-' }}
                                                </p>
                                                <p class="text-xs text-gray-500">{{ $visit->visit_type }} • {{ $visit->payment_type }}</p>
                                            </div>
                                            <div class="text-right">
                                                <p class="text-xs text-gray-600">BP</p>
                                                <p class="text-sm font-semibold {{ ($visit->blood_pressure_systolic >= 140 || $visit->blood_pressure_diastolic >= 90) ? 'text-red-600' : 'text-green-600' }}">
                                                    {{ $visit->blood_pressure_systolic ?? '-' }}/{{ $visit->blood_pressure_diastolic ?? '-' }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-sm text-gray-500 text-center py-4">Belum ada kunjungan KB</div>
                                @endforelse
                            </div>
                        </div>

                        {{-- Footer Actions --}}
                        <div class="flex gap-3 pt-4 border-t">
                            <a href="{{ route('patients.show', $patient->id) }}"
                                class="flex-1 flex items-center justify-center gap-2 px-4 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-colors">
                                <x-heroicon-s-eye class="w-4 h-4" />
                                Lihat Detail Lengkap
                            </a>
                            <a href="{{ route('kb.entry') }}?patient={{ $patient->id }}"
                                class="flex-1 flex items-center justify-center gap-2 px-4 py-3 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg transition-colors">
                                <x-heroicon-s-plus class="w-4 h-4" />
                                Buat Kunjungan Baru
                            </a>
                        </div>
                    @else
                        <div class="p-8 text-center">
                            <x-heroicon-o-user-circle class="w-16 h-16 text-gray-300 mx-auto mb-3" />
                            <p class="text-gray-500">Pasien tidak ditemukan.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif
</div>
