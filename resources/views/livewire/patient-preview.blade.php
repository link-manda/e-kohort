<div>
    <div x-data x-show="{{ json_encode(false) }}">
        {{-- placeholder for Alpine/Livewire hydration --}}
    </div>

    @if ($open)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <!-- Backdrop -->
            <div class="absolute inset-0 bg-black opacity-50"></div>

            <!-- Modal Content -->
            <div class="relative bg-white rounded-lg shadow-xl max-w-3xl w-full max-h-[90vh] overflow-auto z-10">
                <div class="px-6 py-4 border-b flex justify-between items-center sticky top-0 bg-white z-20">
                    <div>
                        <h3 class="text-lg font-semibold">Preview Pasien</h3>
                        <p class="text-sm text-gray-600">Ringkasan pasien dan kunjungan KB terbaru</p>
                    </div>
                    <div>
                        <button wire:click="close" class="px-3 py-1 rounded bg-gray-100">Tutup</button>
                    </div>
                </div>

                <div class="p-6">
                    @if ($patient)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div class="bg-gray-50 p-4 rounded">
                                <p class="text-xs text-gray-500">Nama</p>
                                <p class="font-medium">{{ $patient->name }}</p>
                                <p class="text-xs text-gray-500">No RM</p>
                                <p class="font-medium">{{ $patient->no_rm }}</p>
                                <p class="text-xs text-gray-500">Telepon</p>
                                <p>{{ $patient->phone }}</p>
                            </div>
                            <div class="bg-gray-50 p-4 rounded">
                                <p class="text-xs text-gray-500">NIK</p>
                                <p class="font-medium">{{ $patient->nik ?? '-' }}</p>
                                <p class="text-xs text-gray-500 mt-2">Alamat</p>
                                <p class="text-sm">{{ $patient->address ?? '-' }}</p>
                            </div>
                        </div>

                        <div class="mb-4">
                            <h4 class="font-semibold">Kunjungan KB Terakhir</h4>
                            <div class="mt-2 space-y-2">
                                @forelse($patient->kbVisits->take(5) as $visit)
                                    <div class="p-3 border rounded flex justify-between items-center">
                                        <div>
                                            <div class="text-sm font-medium">
                                                {{ optional($visit->visit_date)->format('d/m/Y') }} —
                                                {{ $visit->kbMethod->name ?? '-' }}</div>
                                            <div class="text-xs text-gray-500">{{ $visit->visit_type }} •
                                                {{ $visit->payment_type }}</div>
                                        </div>
                                        <div class="text-sm text-gray-600">Tensi:
                                            {{ $visit->blood_pressure_systolic ?? '-' }}/{{ $visit->blood_pressure_diastolic ?? '-' }}
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-sm text-gray-500">Belum ada kunjungan KB</div>
                                @endforelse
                            </div>
                        </div>

                        <div class="flex justify-end space-x-2">
                            <a href="{{ route('patients.show', $patient->id) }}"
                                class="px-3 py-2 bg-blue-600 text-white rounded">Lihat Pasien</a>
                            <a href="{{ route('kb.entry') }}?patient={{ $patient->id }}"
                                class="px-3 py-2 border rounded">Buat Kunjungan</a>
                        </div>
                    @else
                        <div class="p-4 text-sm text-gray-500">Pasien tidak ditemukan.</div>
                    @endif
                </div>
            </div>
        </div>
    @endif
</div>
