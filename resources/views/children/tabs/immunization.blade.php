@php
    // Get immunization data from child visits
    $immunizationVisits = $child->childVisits ?? collect();
    $immunizationActions = $immunizationVisits->flatMap->immunizationActions ?? collect();
    $completedVaccines = $immunizationActions->pluck('vaccine_type')->unique();

    // Required vaccines list
    $requiredVaccines = [
        'HB 0' => 'Hepatitis B (Lahir)',
        'BCG' => 'BCG',
        'Polio 1' => 'Polio 1',
        'DPT-HB-Hib 1' => 'DPT-HB-Hib 1',
        'Polio 2' => 'Polio 2',
        'DPT-HB-Hib 2' => 'DPT-HB-Hib 2',
        'Polio 3' => 'Polio 3',
        'DPT-HB-Hib 3' => 'DPT-HB-Hib 3',
        'Polio 4' => 'Polio 4',
        'IPV' => 'IPV',
        'Campak-Rubella' => 'Campak Rubella',
    ];

    $totalRequired = count($requiredVaccines);
    $totalCompleted = $completedVaccines->count();
    $progressPercent = $totalRequired > 0 ? round(($totalCompleted / $totalRequired) * 100) : 0;
@endphp

<div class="space-y-6">
    <!-- Immunization Progress -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
            <x-heroicon-o-shield-check class="w-5 h-5 text-green-600" />
            Progress Imunisasi Dasar
        </h3>

        <div class="mb-4">
            <div class="flex justify-between items-center mb-2">
                <span class="text-sm text-gray-600">{{ $totalCompleted }} dari {{ $totalRequired }} vaksin lengkap</span>
                <span class="text-sm font-bold {{ $progressPercent >= 100 ? 'text-green-600' : 'text-gray-700' }}">{{ $progressPercent }}%</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-3">
                <div class="h-3 rounded-full transition-all {{ $progressPercent >= 100 ? 'bg-green-500' : 'bg-blue-500' }}"
                     style="width: {{ $progressPercent }}%"></div>
            </div>
        </div>

        <!-- Vaccines Grid -->
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
            @foreach($requiredVaccines as $code => $name)
                @php
                    $isCompleted = $completedVaccines->contains($code);
                @endphp
                <div class="flex items-center gap-2 p-3 rounded-lg {{ $isCompleted ? 'bg-green-50 border border-green-200' : 'bg-gray-50 border border-gray-200' }}">
                    @if($isCompleted)
                        <x-heroicon-s-check-circle class="w-5 h-5 text-green-600 flex-shrink-0" />
                    @else
                        <x-heroicon-o-clock class="w-5 h-5 text-gray-400 flex-shrink-0" />
                    @endif
                    <span class="text-sm {{ $isCompleted ? 'text-green-800 font-medium' : 'text-gray-600' }}">{{ $name }}</span>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Immunization History -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                <x-heroicon-o-calendar-days class="w-5 h-5 text-blue-600" />
                Riwayat Kunjungan Imunisasi
            </h3>
        </div>

        @if($immunizationVisits->count() > 0)
            <div class="divide-y divide-gray-200">
                @foreach($immunizationVisits->sortByDesc('visit_date') as $visit)
                    <div class="p-4 hover:bg-gray-50 transition-colors">
                        <div class="flex items-start justify-between">
                            <div>
                                <p class="font-medium text-gray-900">
                                    {{ $visit->visit_date?->locale('id')->isoFormat('dddd, D MMMM YYYY') }}
                                </p>
                                <p class="text-sm text-gray-500 mt-1">
                                    Usia saat kunjungan: {{ $child->getAgeAtVisit($visit->visit_date) }}
                                </p>
                                @if($visit->immunizationActions->count() > 0)
                                    <div class="flex flex-wrap gap-2 mt-2">
                                        @foreach($visit->immunizationActions as $action)
                                            <span class="inline-flex items-center px-2 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">
                                                <x-heroicon-s-check class="w-3 h-3 mr-1" />
                                                {{ $action->vaccine_type }}
                                            </span>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                            <a href="{{ route('imunisasi.kunjungan', $child) }}"
                               class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                Detail →
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="p-8 text-center text-gray-500">
                <x-heroicon-o-shield-exclamation class="w-12 h-12 mx-auto mb-3 text-gray-300" />
                <p class="font-medium">Belum ada riwayat imunisasi</p>
                <p class="text-sm mt-1">Klik tombol "Daftar Imunisasi" untuk mencatat imunisasi pertama</p>
            </div>
        @endif
    </div>
</div>
