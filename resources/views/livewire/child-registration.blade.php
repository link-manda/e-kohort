<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <!-- Breadcrumb -->
    <x-breadcrumb :items="[
        ['label' => 'Data Pasien Anak', 'url' => route('imunisasi.index')],
        ['label' => 'Registrasi Anak Baru'],
    ]" />

    <!-- Header -->
    <div class="mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">👶 Registrasi Anak Baru</h2>
                <p class="text-sm text-gray-500 mt-1">Daftarkan anak untuk pencatatan imunisasi</p>
            </div>
            <a href="{{ route('imunisasi.index') }}"
                class="inline-flex items-center justify-center gap-2 px-4 py-3 md:py-2 min-h-[44px] bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-lg transition-colors shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                <span class="hidden sm:inline">Kembali ke Data Anak</span>
                <span class="sm:hidden">Kembali</span>
            </a>
        </div>
    </div>

    @if ($showSuccess)
        <!-- Success Message -->
        <div class="bg-green-50 border-2 border-green-500 rounded-xl p-6 mb-6 text-center" x-data
            x-init="setTimeout(() => window.location.href = '{{ route('children.immunization', $savedChildId) }}', 2000)">
            <div class="w-16 h-16 bg-green-500 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-green-900 mb-2">Berhasil Didaftarkan!</h3>
            <p class="text-green-700 mb-4">Data anak telah disimpan</p>
            <p class="text-sm text-green-600">Mengalihkan ke halaman imunisasi...</p>
        </div>
    @else
        <!-- Form Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 md:p-8">
            <form wire:submit.prevent="submit">
                <div class="space-y-6">
                    <!-- Step 1: Search & Select Mother -->
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            Cari Data Ibu
                        </h3>

                        @if (!$selectedMother)
                            <div class="relative">
                                <input type="text" wire:model.live="searchTerm"
                                    class="w-full px-4 py-3 pl-10 border-2 rounded-lg border-gray-300
                                              focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-colors"
                                    placeholder="Ketik nama, NIK, atau no. telepon ibu (min 3 karakter)">
                                <svg class="w-5 h-5 text-gray-400 absolute left-3 top-4" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>

                            @if (count($searchResults) > 0)
                                <div class="mt-3 border-2 border-blue-500 rounded-lg max-h-64 overflow-y-auto">
                                    @foreach ($searchResults as $mother)
                                        <button type="button" wire:click="selectMother({{ $mother->id }})"
                                            class="w-full px-4 py-3 text-left hover:bg-blue-50 transition-colors border-b border-gray-200 last:border-0">
                                            <p class="font-semibold text-gray-900">{{ $mother->name }}</p>
                                            <p class="text-sm text-gray-600">NIK: {{ $mother->nik ?: '-' }} |
                                                Telepon: {{ $mother->phone }}</p>
                                        </button>
                                    @endforeach
                                </div>
                            @elseif(strlen($searchTerm) >= 3)
                                <p class="mt-3 text-sm text-gray-500 text-center py-4">Tidak ada hasil yang
                                    ditemukan</p>
                            @endif

                            @error('patient_id')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        @else
                            <!-- Selected Mother -->
                            <div
                                class="bg-blue-50 border-2 border-blue-500 rounded-lg p-4 flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-12 h-12 bg-blue-600 rounded-full flex items-center justify-center text-white font-bold text-lg">
                                        {{ strtoupper(substr($selectedMother->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="font-bold text-gray-900">{{ $selectedMother->name }}</p>
                                        <p class="text-sm text-gray-600">{{ $selectedMother->phone }}</p>
                                    </div>
                                </div>
                                <button type="button" wire:click="clearMotherSelection"
                                    class="text-red-600 hover:text-red-800 transition-colors">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                        @endif
                    </div>

                    @if ($selectedMother)
                        <hr class="border-gray-300">

                        <!-- Step 2: Child Data -->
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                    </path>
                                </svg>
                                Data Anak
                            </h3>

                            <!-- NIK (Optional) -->
                            <div class="mb-4">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    NIK Anak <span class="text-gray-400 text-xs">(Opsional)</span>
                                </label>
                                <input type="text" wire:model="nik" maxlength="16"
                                    class="w-full px-4 py-3 border-2 rounded-lg font-mono
                                              @error('nik') border-red-500 @else border-gray-300 @enderror
                                              focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-colors"
                                    placeholder="16 digit - Kosongkan jika belum punya NIK">
                                @error('nik')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @else
                                    <p class="mt-1 text-xs text-gray-500">Bayi baru lahir biasanya belum memiliki
                                        NIK
                                    </p>
                                @enderror
                            </div>

                            <!-- Name & Gender -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Nama Lengkap Anak <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" wire:model="name"
                                        class="w-full px-4 py-3 border-2 rounded-lg
                                                  @error('name') border-red-500 @else border-gray-300 @enderror
                                                  focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-colors"
                                        placeholder="Nama sesuai akta kelahiran">
                                    @error('name')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Jenis Kelamin <span class="text-red-500">*</span>
                                    </label>
                                    <select wire:model="gender"
                                        class="w-full px-4 py-3 border-2 rounded-lg
                                                   @error('gender') border-red-500 @else border-gray-300 @enderror
                                                   focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-colors">
                                        <option value="">Pilih jenis kelamin</option>
                                        <option value="L">Laki-laki</option>
                                        <option value="P">Perempuan</option>
                                    </select>
                                    @error('gender')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- DOB & POB -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Tanggal Lahir <span class="text-red-500">*</span>
                                    </label>
                                    <input type="date" wire:model="dob" max="{{ date('Y-m-d') }}"
                                        class="w-full px-4 py-3 border-2 rounded-lg
                                                  @error('dob') border-red-500 @else border-gray-300 @enderror
                                                  focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-colors">
                                    @error('dob')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Tempat Lahir
                                    </label>
                                    <input type="text" wire:model="pob"
                                        class="w-full px-4 py-3 border-2 rounded-lg border-gray-300
                                                  focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-colors"
                                        placeholder="Nama kota/kabupaten">
                                </div>
                            </div>

                            <!-- Birth Weight & Height -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Berat Badan Lahir (gram)
                                    </label>
                                    <input type="number" wire:model="birth_weight" step="1" min="500"
                                        max="10000"
                                        class="w-full px-4 py-3 border-2 rounded-lg border-gray-300
                                                  focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-colors"
                                        placeholder="Contoh: 3200">
                                    @error('birth_weight')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @else
                                        <p class="mt-1 text-xs text-gray-500">Normal: 2500 - 4000 gram</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Panjang Badan Lahir (cm)
                                    </label>
                                    <input type="number" wire:model="birth_height" step="0.1" min="20"
                                        max="100"
                                        class="w-full px-4 py-3 border-2 rounded-lg border-gray-300
                                                  focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-colors"
                                        placeholder="Contoh: 50">
                                    @error('birth_height')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @else
                                        <p class="mt-1 text-xs text-gray-500">Normal: 48 - 52 cm</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex gap-4 pt-6 border-t border-gray-200">
                            <button type="submit" wire:loading.attr="disabled" wire:target="submit"
                                class="flex-1 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg
                                           transition-colors shadow-lg hover:shadow-xl flex items-center justify-center gap-2
                                           disabled:opacity-50 disabled:cursor-not-allowed">
                                <!-- Loading Spinner -->
                                <svg wire:loading wire:target="submit" class="animate-spin h-5 w-5 text-white"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>

                                <!-- Default Icon -->
                                <svg wire:loading.remove wire:target="submit" class="w-5 h-5" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>

                                <!-- Button Text -->
                                <span wire:loading.remove wire:target="submit">
                                    Daftarkan Anak
                                </span>
                                <span wire:loading wire:target="submit">
                                    Menyimpan...
                                </span>
                            </button>
                        </div>
                    @endif
                </div>
            </form>
        </div>
    @endif
</div>
