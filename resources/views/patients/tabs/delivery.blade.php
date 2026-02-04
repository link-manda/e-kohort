<!-- Riwayat Persalinan & Nifas -->
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
            <x-heroicon-o-sparkles class="w-6 h-6 text-purple-600" />
            Riwayat Persalinan & Perawatan Nifas
        </h3>
    </div>

    @php
        $deliveredPregnancies = $patient->pregnancies->where('status', 'Lahir')->sortByDesc('delivery_date');
    @endphp

    @if ($deliveredPregnancies->count() > 0)
        @foreach ($deliveredPregnancies as $pregnancy)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <!-- Delivery Header -->
                <div class="bg-gradient-to-r from-purple-500 to-purple-600 p-4 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <h4 class="font-bold text-lg">Persalinan ke-{{ $pregnancy->para }}</h4>
                            @if ($pregnancy->delivery_date)
                                <p class="text-sm opacity-90">
                                    {{ $pregnancy->delivery_date->locale('id')->isoFormat('dddd, D MMMM YYYY') }}
                                </p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Delivery Info -->
                <div class="p-4">
                    @if ($pregnancy->deliveryRecord)
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-4">
                            <div class="text-center p-3 bg-blue-50 rounded-lg">
                                <p class="text-xs text-blue-600 font-medium mb-1">Cara Persalinan</p>
                                <p class="text-sm font-bold text-blue-900">
                                    {{ $pregnancy->deliveryRecord->delivery_method }}</p>
                            </div>
                            <div class="text-center p-3 bg-green-50 rounded-lg">
                                <p class="text-xs text-green-600 font-medium mb-1">Penolong</p>
                                <p class="text-sm font-bold text-green-900">
                                    {{ $pregnancy->deliveryRecord->birth_attendant }}</p>
                            </div>
                            <div class="text-center p-3 bg-orange-50 rounded-lg">
                                <p class="text-xs text-orange-600 font-medium mb-1">Berat Lahir</p>
                                <p class="text-sm font-bold text-orange-900">
                                    {{ number_format($pregnancy->deliveryRecord->birth_weight ?? 0, 0) }} gram</p>
                            </div>
                            <div class="text-center p-3 bg-pink-50 rounded-lg">
                                <p class="text-xs text-pink-600 font-medium mb-1">Kondisi Bayi</p>
                                <p class="text-sm font-bold text-pink-900">{{ $pregnancy->deliveryRecord->condition }}
                                </p>
                            </div>
                        </div>
                    @endif

                    <!-- Postnatal Visits -->
                    @if ($pregnancy->postnatalVisits && $pregnancy->postnatalVisits->count() > 0)
                        <div class="border-t border-gray-200 pt-4">
                            <p class="text-sm font-semibold text-gray-700 mb-3">Kunjungan Nifas
                                ({{ $pregnancy->postnatalVisits->count() }} kali):</p>
                            <div class="space-y-2">
                                @foreach ($pregnancy->postnatalVisits->sortByDesc('visit_date') as $visit)
                                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                        <div class="flex items-center gap-3">
                                            <span
                                                class="px-2 py-1 bg-purple-100 text-purple-800 text-xs font-semibold rounded">
                                                {{ $visit->visit_code }}
                                            </span>
                                            <div>
                                                <p class="text-sm font-medium text-gray-900">
                                                    {{ $visit->visit_date->format('d/m/Y') }}
                                                </p>
                                                <p class="text-xs text-gray-600">
                                                    Hari ke-{{ $visit->days_post_partum ?? '-' }} pasca persalinan
                                                </p>
                                            </div>
                                        </div>
                                        <span
                                            class="text-sm {{ $visit->td_systolic && $visit->td_systolic < 140 ? 'text-green-600' : 'text-red-600' }} font-medium">
                                            {{ $visit->td_systolic ?? '-' }}/{{ $visit->td_diastolic ?? '-' }} mmHg
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <p class="text-center text-gray-500 py-4">Belum ada kunjungan nifas</p>
                    @endif
                </div>
            </div>
        @endforeach
    @else
        <!-- Empty State -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
            <x-heroicon-o-sparkles class="w-16 h-16 text-gray-300 mx-auto mb-4" />
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Belum Ada Data Persalinan</h3>
            <p class="text-gray-600">Pasien ini belum memiliki riwayat persalinan yang tercatat</p>
        </div>
    @endif
</div>
