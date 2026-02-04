<!-- Riwayat Umum (General Visits) -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
    <div class="flex items-center justify-between mb-6">
        <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
            <x-heroicon-o-clipboard-document-check class="w-6 h-6 text-blue-600" />
            Riwayat Kunjungan Poli Umum
        </h3>
        <a href="{{ route('registration-desk') }}?patient_id={{ $patient->id }}"
            class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-colors text-sm">
            <x-heroicon-o-plus class="w-4 h-4" />
            Tambah Kunjungan
        </a>
    </div>

    @if ($patient->generalVisits && $patient->generalVisits->count() > 0)
        <div class="space-y-4">
            @foreach ($patient->generalVisits as $visit)
                <div class="border border-gray-200 rounded-lg p-4 hover:border-blue-300 transition-colors">
                    <!-- Visit Header -->
                    <div class="flex items-start justify-between mb-3">
                        <div>
                            <div class="flex items-center gap-2 mb-1">
                                <span class="text-sm font-semibold text-gray-900">
                                    {{ $visit->visit_date->locale('id')->isoFormat('dddd, D MMMM YYYY') }}
                                </span>
                                <span class="text-xs text-gray-500">
                                    {{ $visit->visit_date->format('H:i') }} WIB
                                </span>
                            </div>
                            <div class="flex items-center gap-2">
                                <!-- Status Badge -->
                                @if ($visit->status === 'Pulang')
                                    <span
                                        class="inline-flex items-center px-2 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">
                                        ✓ Pulang
                                    </span>
                                @elseif($visit->status === 'Rujuk')
                                    <span
                                        class="inline-flex items-center px-2 py-1 bg-orange-100 text-orange-800 text-xs font-semibold rounded-full">
                                        → Rujuk
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center px-2 py-1 bg-red-100 text-red-800 text-xs font-semibold rounded-full">
                                        🏥 Rawat Inap
                                    </span>
                                @endif

                                <!-- Payment Badge -->
                                @if ($visit->payment_method === 'BPJS')
                                    <span
                                        class="inline-flex items-center px-2 py-1 bg-blue-100 text-blue-800 text-xs font-semibold rounded-full">
                                        BPJS
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center px-2 py-1 bg-gray-100 text-gray-800 text-xs font-semibold rounded-full">
                                        Umum
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Visit Details -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <!-- Subjective -->
                        <div class="col-span-2">
                            <p class="text-xs font-semibold text-gray-500 uppercase mb-1">Keluhan (Subjective)</p>
                            <p class="text-gray-900">{{ $visit->complaint }}</p>
                        </div>

                        <!-- Objective - Vital Signs -->
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase mb-2">Tanda Vital (Objective)</p>
                            <div class="space-y-1 text-xs">
                                @if ($visit->systolic && $visit->diastolic)
                                    <p><span class="text-gray-600">Tensi:</span> <span
                                            class="font-medium text-gray-900">{{ $visit->systolic }}/{{ $visit->diastolic }}
                                            mmHg</span></p>
                                @endif
                                @if ($visit->temperature)
                                    <p><span class="text-gray-600">Suhu:</span> <span
                                            class="font-medium text-gray-900">{{ $visit->temperature }}°C</span></p>
                                @endif
                                @if ($visit->weight)
                                    <p><span class="text-gray-600">BB:</span> <span
                                            class="font-medium text-gray-900">{{ $visit->weight }} kg</span></p>
                                @endif
                                @if ($visit->height)
                                    <p><span class="text-gray-600">TB:</span> <span
                                            class="font-medium text-gray-900">{{ $visit->height }} cm</span></p>
                                @endif
                            </div>
                        </div>

                        <!-- Assessment -->
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase mb-2">Diagnosa (Assessment)</p>
                            <p class="text-gray-900 font-medium">{{ $visit->diagnosis }}</p>
                            @if ($visit->icd10_code)
                                <p class="text-xs text-gray-600 mt-1">Kode ICD-10: <span
                                        class="font-mono">{{ $visit->icd10_code }}</span></p>
                            @endif
                        </div>

                        <!-- Plan -->
                        @if ($visit->therapy)
                            <div class="col-span-2">
                                <p class="text-xs font-semibold text-gray-500 uppercase mb-1">Terapi/Tindakan (Plan)</p>
                                <p class="text-gray-900">{{ $visit->therapy }}</p>
                            </div>
                        @endif

                        <!-- Prescriptions (Resep Obat) -->
                        @if ($visit->prescriptions && $visit->prescriptions->count() > 0)
                            <div class="col-span-2">
                                <p class="text-xs font-semibold text-gray-500 uppercase mb-2">💊 Resep Obat
                                    ({{ $visit->prescriptions->count() }} item)</p>
                                <div class="bg-green-50 border border-green-200 rounded-md p-3 space-y-2">
                                    @foreach ($visit->prescriptions as $rx)
                                        <div class="flex justify-between items-start text-xs">
                                            <div class="flex-1">
                                                <p class="font-semibold text-gray-900">{{ $rx->medicine_name }}</p>
                                                <p class="text-gray-600">
                                                    {{ $rx->quantity }}
                                                    @if ($rx->dosage)
                                                        • {{ $rx->dosage }}
                                                    @endif
                                                    @if ($rx->signa)
                                                        • {{ $rx->signa }}
                                                    @endif
                                                </p>
                                                @if ($rx->duration)
                                                    <p class="text-gray-500 text-xs">Durasi: {{ $rx->duration }}</p>
                                                @endif
                                            </div>
                                            <div class="text-right ml-3">
                                                <p class="font-bold text-green-700">Rp
                                                    {{ number_format($rx->total_price, 0, ',', '.') }}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                    <div class="pt-2 border-t border-green-300 flex justify-between items-center">
                                        <p class="text-xs font-semibold text-gray-700">Total Biaya Obat:</p>
                                        <p class="text-sm font-bold text-green-700">
                                            Rp
                                            {{ number_format($visit->prescriptions->sum('total_price'), 0, ',', '.') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Physical Exam (if exists) -->
                        @if ($visit->physical_exam)
                            <div class="col-span-2">
                                <p class="text-xs font-semibold text-gray-500 uppercase mb-1">Pemeriksaan Fisik</p>
                                <p class="text-gray-900">{{ $visit->physical_exam }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach

            <!-- View All Link -->
            @if ($patient->generalVisits->count() >= 10)
                <div class="text-center pt-4">
                    <a href="{{ route('general-visits.index') }}?search={{ $patient->nik ?? $patient->name }}"
                        class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-700 font-medium text-sm">
                        Lihat Semua Riwayat Poli Umum
                        <x-heroicon-o-arrow-right class="w-4 h-4" />
                    </a>
                </div>
            @endif
        </div>
    @else
        <!-- Empty State -->
        <div class="text-center py-12">
            <x-heroicon-o-clipboard-document-list class="w-16 h-16 text-gray-300 mx-auto mb-4" />
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Belum Ada Kunjungan Poli Umum</h3>
            <p class="text-gray-600 mb-6">Pasien ini belum pernah melakukan kunjungan ke Poli Umum</p>
            <a href="{{ route('registration-desk') }}?patient_id={{ $patient->id }}"
                class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-colors">
                <x-heroicon-o-plus class="w-5 h-5" />
                Daftarkan Kunjungan Pertama
            </a>
        </div>
    @endif
</div>
