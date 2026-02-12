<!-- Riwayat KB (Keluarga Berencana) -->
<div class="space-y-6">
    <div>
        <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
            <x-heroicon-o-check-circle class="w-6 h-6 text-green-600" />
            Riwayat KB (Keluarga Berencana)
        </h3>
        <p class="text-sm text-gray-600 mt-1">Pendaftaran KB dilakukan melalui <a href="{{ route('registration-desk') }}" class="text-blue-600 hover:text-blue-800 font-medium underline">Registration Desk</a></p>
    </div>

    @if ($kbVisits && $kbVisits->count() > 0)
        <!-- Dashboard Section -->
        @php
            $latestKb = $kbVisits->sortByDesc('visit_date')->first();
            $nextVisit = $latestKb ? $latestKb->next_visit_date : null;
            $daysToNextVisit = $nextVisit ? now()->diffInDays($nextVisit, false) : null;
            $isOverdue = $daysToNextVisit && $daysToNextVisit < 0;
            $isUpcoming = $daysToNextVisit && $daysToNextVisit >= 0 && $daysToNextVisit <= 7;
        @endphp

        <div class="mb-6 border rounded-xl overflow-hidden shadow-sm bg-white">
            <div class="bg-gray-50 px-4 py-3 border-b flex justify-between items-center">
                <h5 class="font-bold text-gray-800 flex items-center gap-2">
                    <x-heroicon-o-presentation-chart-line class="w-5 h-5 text-blue-600" />
                    Status Terkini & Jadwal
                </h5>
                @if($latestKb && $latestKb->visit_type == 'Peserta Baru')
                     <span class="px-2.5 py-1 bg-green-100 text-green-700 text-xs font-bold rounded-full border border-green-200">
                        Peserta Baru
                    </span>
                @endif
            </div>

            <div class="p-4 grid grid-cols-1 md:grid-cols-3 gap-4">
                {{-- 1. Metode Aktif --}}
                <div class="bg-blue-50 rounded-xl p-4 border border-blue-100 flex items-start gap-3">
                    <div class="p-2 bg-white rounded-lg shadow-sm">
                        <x-heroicon-o-shield-check class="w-6 h-6 text-blue-600" />
                    </div>
                    <div>
                        <p class="text-xs text-blue-600 font-bold uppercase tracking-wider">Metode Aktif</p>
                        <h4 class="text-lg font-bold text-gray-900 mt-1">{{ $latestKb->kbMethod->name ?? '-' }}</h4>
                        <p class="text-xs text-gray-600 mt-1">
                            Sejak: {{ $latestKb->visit_date->format('d M Y') }}
                        </p>
                    </div>
                </div>

                {{-- 2. Jadwal Kunjungan Berikutnya --}}
                <div class="bg-purple-50 rounded-xl p-4 border border-purple-100 flex items-start gap-3 relative overflow-hidden">
                    <div class="p-2 bg-white rounded-lg shadow-sm z-10">
                        <x-heroicon-o-calendar-days class="w-6 h-6 text-purple-600" />
                    </div>
                    <div class="z-10 relative">
                        <p class="text-xs text-purple-600 font-bold uppercase tracking-wider">Kunjungan Berikutnya</p>
                        @if ($nextVisit)
                            <h4 class="text-lg font-bold {{ $isOverdue ? 'text-red-600' : 'text-gray-900' }} mt-1">
                                {{ $nextVisit->format('d M Y') }}
                            </h4>
                            <p class="text-xs font-medium {{ $isOverdue ? 'text-red-600' : ($isUpcoming ? 'text-orange-600' : 'text-gray-600') }} mt-1">
                                @if($isOverdue)
                                    ⚠️ Terlewat {{ abs(intval($daysToNextVisit)) }} hari
                                @elseif($daysToNextVisit == 0)
                                    📅 HARI INI
                                @else
                                    {{ intval($daysToNextVisit) }} hari lagi
                                @endif
                            </p>
                        @else
                            <p class="text-sm text-gray-500 italic mt-1">Belum dijadwalkan</p>
                        @endif
                    </div>
                    @if($isOverdue)
                        <div class="absolute -right-4 -top-4 w-16 h-16 bg-red-100 rounded-full opacity-50"></div>
                    @endif
                </div>

                {{-- 3. Vital Sign Terakhir --}}
                <div class="bg-gray-50 rounded-xl p-4 border border-gray-200 flex items-start gap-3">
                     <div class="p-2 bg-white rounded-lg shadow-sm">
                        <x-heroicon-o-heart class="w-6 h-6 text-red-500" />
                    </div>
                    <div class="w-full">
                        <p class="text-xs text-gray-500 font-bold uppercase tracking-wider">Tanda Vital Terakhir</p>
                        <div class="flex justify-between items-end mt-1">
                            <div>
                                <span class="text-lg font-bold text-gray-900">{{ $latestKb->blood_pressure_systolic }}/{{ $latestKb->blood_pressure_diastolic }}</span>
                                <span class="text-xs text-gray-500">mmHg</span>
                            </div>
                            <div class="text-right">
                                <span class="text-lg font-bold text-gray-900">{{ $latestKb->weight }}</span>
                                <span class="text-xs text-gray-500">kg</span>
                            </div>
                        </div>
                        @if($latestKb->isHypertensive())
                             <div class="mt-1 inline-flex items-center gap-1 px-1.5 py-0.5 bg-red-100 text-red-700 text-[10px] font-bold rounded">
                                <x-heroicon-s-exclamation-circle class="w-3 h-3" />
                                Hipertensi
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- KB Visits Cards -->
        <h4 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
            <x-heroicon-o-clock class="w-5 h-5 text-gray-500" />
            Riwayat Kunjungan
        </h4>

        <div class="space-y-4">
            @foreach ($kbVisits->sortByDesc('visit_date') as $visit)
                <div class="bg-white border rounded-xl overflow-hidden hover:shadow-md transition-shadow group relative">
                     {{-- Side Indicator based on Outcome --}}
                    <div class="absolute left-0 top-0 bottom-0 w-1 {{ $visit->complications || $visit->side_effects ? 'bg-orange-400' : 'bg-green-500' }}"></div>

                    <div class="pl-3">
                        {{-- Card Header --}}
                        <div class="px-4 py-3 border-b border-gray-100 flex justify-between items-start bg-gray-50/50">
                            <div>
                                <div class="flex items-center gap-2">
                                    <h5 class="font-bold text-gray-900 text-sm">
                                        {{ $visit->visit_date->format('d M Y') }}
                                    </h5>
                                    @if($visit->visit_type == 'Peserta Baru')
                                        <span class="px-1.5 py-0.5 bg-blue-100 text-blue-700 text-[10px] font-bold rounded border border-blue-200">Baru</span>
                                    @else
                                        <span class="px-1.5 py-0.5 bg-gray-100 text-gray-600 text-[10px] font-bold rounded border border-gray-200">Ulang</span>
                                    @endif
                                </div>
                                <p class="text-xs text-gray-500 mt-0.5">Bidan: {{ $visit->midwife_name ?? '-' }}</p>
                            </div>
                            <div class="text-right">
                                <span class="text-xs text-gray-400 block">Biaya Layanan</span>
                                <span class="font-bold text-green-600">Rp {{ number_format($visit->service_fee ?? 0, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        {{-- Card Body --}}
                        <div class="p-4 grid grid-cols-1 md:grid-cols-3 gap-6">
                            {{-- Col 1: Method & Stats --}}
                            <div>
                                <p class="text-[10px] uppercase font-bold text-gray-400 tracking-wider mb-2">Metode & Fisik</p>
                                <div class="space-y-2">
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 rounded-full bg-blue-50 flex items-center justify-center">
                                             <x-heroicon-s-shield-check class="w-4 h-4 text-blue-600" />
                                        </div>
                                        <div>
                                            <p class="text-xs font-bold text-gray-700">{{ $visit->kbMethod->name ?? '-' }}</p>
                                            <p class="text-[10px] text-gray-500">{{ $visit->contraception_brand ?? '-' }}</p>
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-2 gap-2 mt-2">
                                         <div class="bg-gray-50 p-2 rounded text-center">
                                            <span class="block text-[10px] text-gray-500">BB (Kg)</span>
                                            <span class="font-bold text-gray-800 text-sm">{{ $visit->weight }}</span>
                                        </div>
                                        <div class="bg-gray-50 p-2 rounded text-center">
                                            <span class="block text-[10px] text-gray-500">TD (mmHg)</span>
                                            <span class="font-bold text-gray-800 text-sm {{ $visit->isHypertensive() ? 'text-red-600' : '' }}">
                                                {{ $visit->blood_pressure_systolic }}/{{ $visit->blood_pressure_diastolic }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Col 2: Diagnosis & Outcome --}}
                            <div class="md:border-l md:pl-6 border-gray-100">
                                <p class="text-[10px] uppercase font-bold text-gray-400 tracking-wider mb-2">Diagnosa & Keluhan</p>
                                @if($visit->side_effects || $visit->complications || $visit->diagnosis)
                                    <div class="space-y-2 text-xs">
                                        @if($visit->diagnosis)
                                            <p class="text-gray-800"><span class="font-semibold">Diagnosa:</span> {{ $visit->diagnosis }}</p>
                                        @endif

                                        @if($visit->side_effects)
                                            <div class="flex items-start gap-1.5 text-orange-700 bg-orange-50 p-2 rounded-lg">
                                                <x-heroicon-s-exclamation-triangle class="w-4 h-4 flex-shrink-0 mt-0.5" />
                                                <div>
                                                    <span class="font-bold block">Efek Samping:</span>
                                                    {{ $visit->side_effects }}
                                                </div>
                                            </div>
                                        @endif

                                        @if($visit->complications)
                                             <div class="flex items-start gap-1.5 text-red-700 bg-red-50 p-2 rounded-lg">
                                                <x-heroicon-s-exclamation-circle class="w-4 h-4 flex-shrink-0 mt-0.5" />
                                                <div>
                                                    <span class="font-bold block">Komplikasi:</span>
                                                    {{ $visit->complications }}
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                @else
                                    <p class="text-xs text-gray-400 italic">Tidak ada keluhan atau komplikasi.</p>
                                @endif
                            </div>

                            {{-- Col 3: Plan --}}
                            <div class="md:border-l md:pl-6 border-gray-100 flex flex-col justify-between">
                                <div>
                                    <p class="text-[10px] uppercase font-bold text-gray-400 tracking-wider mb-2">Tindak Lanjut</p>
                                    @if($visit->next_visit_date)
                                        <div class="flex items-center gap-2 mb-2">
                                            <x-heroicon-m-calendar class="w-4 h-4 text-purple-500" />
                                            <div>
                                                <p class="text-xs text-gray-500">Kunjungan Ulang:</p>
                                                <p class="text-sm font-bold text-purple-700">{{ $visit->next_visit_date->format('d M Y') }}</p>
                                            </div>
                                        </div>
                                    @else
                                        <p class="text-xs text-gray-400 italic">Tidak ada jadwal ulang.</p>
                                    @endif
                                </div>


                                <a href="{{ route('kb-visits.show', $visit->id) }}" class="mt-4 text-center w-full block px-3 py-2 bg-white border border-gray-300 text-gray-700 text-xs font-bold rounded-lg hover:bg-gray-50 transition-colors">
                                    Lihat Detail
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <!-- Empty State -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
            <x-heroicon-o-clipboard-document-list class="w-16 h-16 text-gray-300 mx-auto mb-4" />
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Belum Ada Riwayat KB</h3>
            <p class="text-gray-600 mb-3">Pasien ini belum pernah menggunakan layanan KB di klinik ini.</p>
            <p class="text-sm text-gray-500">Gunakan <a href="{{ route('registration-desk') }}" class="text-blue-600 hover:text-blue-800 font-medium underline">Registration Desk</a> untuk mendaftarkan layanan KB baru.</p>
        </div>
    @endif
</div>
