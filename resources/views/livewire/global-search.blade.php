<div x-data="{ focused: false }" @click.away="$wire.closeResults()" class="relative w-full max-w-xl hidden md:block">
    <!-- Search Input -->
    <div class="relative">
        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </div>
        <input type="text" wire:model.live.debounce.300ms="search" @focus="focused = true"
            @keydown.escape="$wire.closeResults(); $el.blur()" placeholder="Cari pasien (Nama, NIK, No. RM)..."
            class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">

        <!-- Loading Spinner -->
        <div wire:loading wire:target="search" class="absolute inset-y-0 right-0 flex items-center pr-3">
            <svg class="w-5 h-5 text-blue-500 animate-spin" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                </circle>
                <path class="opacity-75" fill="currentColor"
                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                </path>
            </svg>
        </div>
    </div>

    <!-- Search Results Dropdown -->
    @if ($showResults)
        <div
            class="absolute z-50 w-full mt-2 bg-white rounded-lg shadow-xl border border-gray-200 max-h-96 overflow-y-auto">
            @if (count($results) > 0)
                <div class="py-2">
                    <div class="px-4 py-2 text-xs font-semibold text-gray-500 uppercase">
                        {{ count($results) }} Hasil Ditemukan
                    </div>
                    @foreach ($results as $patient)
                        <a href="{{ route('patients.show', $patient->id) }}"
                            class="flex items-center px-4 py-3 hover:bg-blue-50 transition-colors border-b border-gray-100 last:border-0">
                            <!-- Avatar -->
                            <div
                                class="flex-shrink-0 w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                <span class="text-blue-600 font-semibold text-sm">
                                    {{ strtoupper(substr($patient->name, 0, 1)) }}
                                </span>
                            </div>

                            <!-- Patient Info -->
                            <div class="ml-3 flex-1 min-w-0">
                                <div class="flex items-center gap-2">
                                    <p class="text-sm font-medium text-gray-900 truncate">
                                        {{ $patient->name }}
                                    </p>
                                    <!-- Badge Tanpa NIK -->
                                    @if (empty($patient->nik))
                                        <span
                                            class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800">
                                            Tanpa NIK
                                        </span>
                                    @endif
                                </div>
                                <p class="text-xs text-gray-500">
                                    No. RM: {{ $patient->no_rm }}
                                    @if ($patient->nik)
                                        • NIK: {{ $patient->nik }}
                                    @endif
                                </p>
                                @if ($patient->phone)
                                    <p class="text-xs text-gray-600 font-medium">
                                        📱 {{ $patient->phone }}
                                    </p>
                                @endif
                            </div>

                            <!-- Arrow Icon -->
                            <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                    clip-rule="evenodd" />
                            </svg>
                        </a>
                    @endforeach
                </div>
            @else
                <div class="px-4 py-8 text-center">
                    <svg class="mx-auto w-12 h-12 text-gray-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M12 12h.01M12 12h.01M12 12h.01M12 12h.01M12 12h.01M12 12h.01M12 12h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="mt-2 text-sm text-gray-500">Tidak ada hasil untuk "{{ $search }}"</p>
                    <p class="text-xs text-gray-400 mt-1">Coba kata kunci lain</p>
                </div>
            @endif
        </div>
    @endif
</div>
