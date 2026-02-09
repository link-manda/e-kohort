<div class="max-w-7xl mx-auto px-4 py-6">
    {{-- Breadcrumb Navigation --}}
    <nav class="mb-4 text-sm">
        <ol class="flex items-center gap-2 text-gray-600">
            <li><a href="{{ route('dashboard') }}" class="hover:text-pink-600">Dashboard</a></li>
            <li>/</li>
            <li><a href="{{ route('patients.index') }}" class="hover:text-pink-600">Data Pasien</a></li>
            <li>/</li>
            <li><a href="{{ route('patients.show', $pregnancy->patient_id) }}" class="hover:text-pink-600">{{ $pregnancy->patient->name }}</a></li>
            <li>/</li>
            <li class="text-pink-600 font-semibold">Input Persalinan</li>
        </ol>
    </nav>

    {{-- Pregnancy Context Card --}}
    <div class="bg-gradient-to-r from-purple-50 to-pink-50 border-2 border-purple-300 rounded-xl p-5 mb-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-purple-600 rounded-full flex items-center justify-center text-white text-xl font-bold">
                    🤰
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900">
                        Kehamilan {{ $pregnancy->gravida }} - {{ $pregnancy->patient->name }}
                    </h3>
                    <div class="flex items-center gap-4 text-sm text-gray-700 mt-1">
                        <span class="font-medium">📅 HPHT: {{ $pregnancy->hpht->format('d/m/Y') }}</span>
                        <span class="font-medium">📌 HPL: {{ $pregnancy->hpl->format('d/m/Y') }}</span>
                        <span class="font-medium bg-purple-200 text-purple-900 px-3 py-1 rounded-full">
                            {{ $pregnancy->gestational_age }} Minggu
                        </span>
                    </div>
                </div>
            </div>
            <a href="{{ route('patients.show', $pregnancy->patient_id) }}"
                class="p-2 hover:bg-purple-100 rounded-lg transition-colors">
                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </a>
        </div>
    </div>

    {{-- Header --}}
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">🏥 Form Persalinan & Bayi Baru Lahir</h1>
        <p class="text-sm text-gray-500 mt-1">Dokumentasi lengkap proses persalinan dan kondisi bayi</p>
    </div>

    @if (session()->has('message'))
        <!-- Success Message -->
        <div class="bg-green-50 border-2 border-green-500 rounded-xl p-6 mb-6">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-green-900">{{ session('message') }}</h3>
                    <p class="text-sm text-green-700">Status kehamilan telah diupdate ke "Lahir"</p>
                </div>
            </div>
        </div>
    @endif

    <form wire:submit.prevent="save">
        <!-- SECTION 1: DATA PERSALINAN (IBU) -->
        <div class="bg-white rounded-xl shadow-md border-2 border-pink-300 mb-6">
            <div class="bg-gradient-to-r from-pink-100 to-pink-50 px-6 py-4 border-b-2 border-pink-300">
                <h2 class="text-xl font-bold text-pink-900 flex items-center gap-2">
                    <span>📋</span>
                    <span>Data Persalinan (Ibu)</span>
                </h2>
                <p class="text-sm text-pink-700 mt-1">Informasi proses persalinan Kala I - IV</p>
            </div>

            <div class="p-6 space-y-6">
                <!-- Row 1: Tanggal, Jam, Gestational Age -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Tanggal Lahir <span class="text-red-500">*</span>
                        </label>
                        <input type="date" wire:model="delivery_date"
                            class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-transparent">
                        @error('delivery_date')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Jam Lahir <span class="text-red-500">*</span>
                        </label>
                        <input type="time" wire:model="delivery_time"
                            class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-transparent">
                        @error('delivery_time')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Usia Kehamilan (Minggu) <span class="text-red-500">*</span>
                        </label>
                        <input type="number" wire:model="gestational_age" min="20" max="44"
                            class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-transparent">
                        @error('gestational_age')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Row 2: Penolong, Tempat -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Penolong Persalinan <span class="text-red-500">*</span>
                        </label>
                        <input type="text" wire:model="birth_attendant" placeholder="Contoh: Bidan A"
                            class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-transparent">
                        @error('birth_attendant')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Tempat Lahir <span class="text-red-500">*</span>
                        </label>
                        <input type="text" wire:model="place_of_birth" placeholder="Contoh: Klinik XYZ"
                            class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-transparent">
                        @error('place_of_birth')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Row 3: Kala I & II -->
                <div class="bg-pink-50 rounded-lg p-4 border border-pink-200">
                    <h3 class="font-bold text-pink-900 mb-3">Kala I & II (Durasi Persalinan)</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Lama Kala I (Jam)</label>
                            <input type="text" wire:model="duration_first_stage" placeholder="Contoh: 8"
                                class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500">
                            @error('duration_first_stage')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Lama Kala II (Menit)</label>
                            <input type="text" wire:model="duration_second_stage" placeholder="Contoh: 45"
                                class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500">
                            @error('duration_second_stage')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Cara Lahir <span class="text-red-500">*</span>
                            </label>
                            <select wire:model="delivery_method"
                                class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500">
                                <option value="">-- Pilih --</option>
                                <option value="Spontan Belakang Kepala">Spontan Belakang Kepala</option>
                                <option value="Sungsang">Sungsang</option>
                                <option value="Vakum">Vakum</option>
                                <option value="Sectio Caesarea">Sectio Caesarea</option>
                            </select>
                            @error('delivery_method')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Row 4: Kala III & IV -->
                <div class="bg-pink-50 rounded-lg p-4 border border-pink-200">
                    <h3 class="font-bold text-pink-900 mb-3">Kala III & IV (Plasenta & Jalan Lahir)</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Lahir Plasenta <span class="text-red-500">*</span>
                            </label>
                            <select wire:model="placenta_delivery"
                                class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500">
                                <option value="Spontan">Spontan</option>
                                <option value="Manual">Manual</option>
                                <option value="Sisa">Sisa</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Keadaan Perineum <span class="text-red-500">*</span>
                            </label>
                            <select wire:model="perineum_rupture"
                                class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500">
                                <option value="Utuh">Utuh</option>
                                <option value="Derajat 1">Derajat 1</option>
                                <option value="Derajat 2">Derajat 2</option>
                                <option value="Derajat 3">Derajat 3</option>
                                <option value="Derajat 4">Derajat 4</option>
                                <option value="Episiotomi">Episiotomi</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Estimasi Perdarahan (ml)
                                @if ($bleeding_amount && $bleeding_amount > 500)
                                    <span class="text-red-500 font-bold">⚠️ ALERT: > 500ml!</span>
                                @endif
                            </label>
                            <input type="number" wire:model="bleeding_amount" placeholder="Contoh: 250"
                                class="w-full px-4 py-2 border-2 @if ($bleeding_amount && $bleeding_amount > 500) border-red-500 @else border-gray-300 @endif rounded-lg focus:ring-2 focus:ring-pink-500">
                            @error('bleeding_amount')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Tensi Pasca Salin (Kala IV)
                            </label>
                            <input type="text" wire:model="blood_pressure" placeholder="Contoh: 120/80"
                                class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500">
                            @error('blood_pressure')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Manajemen Aktif Kala 3 (AMTSL) -->
                    <div class="mt-4 pt-4 border-t border-pink-300">
                        <h4 class="font-bold text-pink-900 mb-3 flex items-center gap-2">
                            <span>💉</span>
                            <span>Manajemen Aktif Kala 3 (AMTSL)</span>
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                            <label
                                class="flex items-center gap-3 bg-white p-3 rounded-lg border-2 border-pink-200 cursor-pointer hover:bg-pink-50 transition">
                                <input type="checkbox" wire:model="oxytocin_injection" class="w-5 h-5 text-pink-600">
                                <div>
                                    <span class="font-semibold text-gray-900 text-sm">Suntik Oksitosin 10 IU</span>
                                    <p class="text-xs text-gray-600">IM dalam 1 menit</p>
                                </div>
                            </label>

                            <label
                                class="flex items-center gap-3 bg-white p-3 rounded-lg border-2 border-pink-200 cursor-pointer hover:bg-pink-50 transition">
                                <input type="checkbox" wire:model="controlled_cord_traction"
                                    class="w-5 h-5 text-pink-600">
                                <div>
                                    <span class="font-semibold text-gray-900 text-sm">PTT</span>
                                    <p class="text-xs text-gray-600">Peregangan Tali Pusat Terkendali</p>
                                </div>
                            </label>

                            <label
                                class="flex items-center gap-3 bg-white p-3 rounded-lg border-2 border-pink-200 cursor-pointer hover:bg-pink-50 transition">
                                <input type="checkbox" wire:model="uterine_massage" class="w-5 h-5 text-pink-600">
                                <div>
                                    <span class="font-semibold text-gray-900 text-sm">Masase Uterus</span>
                                    <p class="text-xs text-gray-600">Fundus uteri setelah plasenta lahir</p>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Pemantauan 2 Jam Post Partum & Komplikasi -->
                    <div class="mt-4 pt-4 border-t border-pink-300 grid grid-cols-1 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Pemantauan 2 Jam Post Partum
                            </label>
                            <textarea wire:model="postpartum_monitoring_2h" rows="2"
                                placeholder="Contoh: TFU 2 jari di bawah pusat, kontraksi baik, perdarahan normal"
                                class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500"></textarea>
                            <p class="text-xs text-gray-500 mt-1">TFU, kontraksi uterus, perdarahan, TTV, dll</p>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Penyulit/Komplikasi Persalinan
                            </label>
                            <textarea wire:model="complications" rows="2" placeholder="Contoh: Tidak ada penyulit / Perdarahan Post Partum"
                                class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500"></textarea>
                        </div>
                    </div>

                    <!-- Biaya Layanan -->
                    <div class="mt-4 pt-4 border-t border-pink-300">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Biaya Layanan (Rp)
                        </label>
                        <input type="number" wire:model="service_fee" min="0" step="1000"
                            class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500"
                            placeholder="Biaya jasa persalinan">
                        @error('service_fee')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- SECTION 2: DATA BAYI (OUTCOME) -->
        <div class="bg-white rounded-xl shadow-md border-2 border-blue-300 mb-6">
            <div class="bg-gradient-to-r from-blue-100 to-blue-50 px-6 py-4 border-b-2 border-blue-300">
                <h2 class="text-xl font-bold text-blue-900 flex items-center gap-2">
                    <span>👶</span>
                    <span>Data Bayi Baru Lahir</span>
                </h2>
                <p class="text-sm text-blue-700 mt-1">Kondisi dan data antropometri bayi</p>
            </div>

            <div class="p-6 space-y-6">
                <!-- Row 1: Nama, JK, Kondisi -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Nama Bayi (Opsional)
                        </label>
                        <input type="text" wire:model="baby_name"
                            placeholder="Contoh: By. Ny. {{ $pregnancy->patient->name }}"
                            class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        <p class="text-xs text-gray-500 mt-1">Kosongkan jika belum ada nama</p>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Jenis Kelamin <span class="text-red-500">*</span>
                        </label>
                        <div class="flex gap-4">
                            <label class="flex items-center cursor-pointer">
                                <input type="radio" wire:model="gender" value="L" class="mr-2">
                                <span class="px-4 py-2 border-2 rounded-lg">👦 Laki-laki</span>
                            </label>
                            <label class="flex items-center cursor-pointer">
                                <input type="radio" wire:model="gender" value="P" class="mr-2">
                                <span class="px-4 py-2 border-2 rounded-lg">👧 Perempuan</span>
                            </label>
                        </div>
                        @error('gender')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Kondisi Bayi <span class="text-red-500">*</span>
                        </label>
                        <select wire:model="condition"
                            class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            <option value="Hidup">Hidup</option>
                            <option value="Asfiksia">Asfiksia</option>
                            <option value="Meninggal">Meninggal</option>
                        </select>
                    </div>
                </div>

                <!-- Row 2: Antropometri -->
                <div class="bg-blue-50 rounded-lg p-4 border border-blue-200">
                    <h3 class="font-bold text-blue-900 mb-3">Antropometri Bayi</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Berat Badan (gram) <span class="text-red-500">*</span>
                            </label>
                            <input type="number" wire:model="birth_weight" min="500" max="6000"
                                placeholder="Contoh: 3200"
                                class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            @error('birth_weight')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Panjang Badan (cm) <span class="text-red-500">*</span>
                            </label>
                            <input type="number" wire:model="birth_length" min="30" max="60"
                                step="0.1" placeholder="Contoh: 49.5"
                                class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            @error('birth_length')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Lingkar Kepala (cm)
                            </label>
                            <input type="number" wire:model="head_circumference" min="20" max="45"
                                step="0.1" placeholder="Contoh: 34"
                                class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            @error('head_circumference')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Row 3: APGAR Score -->
                <div class="bg-blue-50 rounded-lg p-4 border border-blue-200">
                    <h3 class="font-bold text-blue-900 mb-3">APGAR Score</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                APGAR Menit ke-1 (0-10)
                            </label>
                            <input type="number" wire:model="apgar_score_1" min="0" max="10"
                                placeholder="Contoh: 8"
                                class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            @if ($apgar_score_1 && $apgar_score_1 < 7)
                                <p class="text-orange-600 text-xs mt-1">⚠️ Perlu perhatian khusus</p>
                            @endif
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                APGAR Menit ke-5 (0-10)
                            </label>
                            <input type="number" wire:model="apgar_score_5" min="0" max="10"
                                placeholder="Contoh: 9"
                                class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            @if ($apgar_score_5 && $apgar_score_5 < 7)
                                <p class="text-red-600 text-xs mt-1">⚠️ Perlu rujukan!</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Row 4: Kelainan Bawaan -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Kelainan Bawaan (jika ada)
                    </label>
                    <textarea wire:model="congenital_defect" rows="2" placeholder="Contoh: Tidak ada kelainan"
                        class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"></textarea>
                </div>

                <!-- Row 5: Checklist Manajemen BBL -->
                <div class="bg-gradient-to-r from-green-50 to-green-100 rounded-lg p-6 border-2 border-green-300">
                    <h3 class="font-bold text-green-900 mb-4 text-lg flex items-center gap-2">
                        <span>✅</span>
                        <span>Manajemen Bayi Baru Lahir (Checklist)</span>
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- IMD -->
                        <label
                            class="flex items-center gap-3 bg-white p-4 rounded-lg border-2 border-green-200 cursor-pointer hover:bg-green-50 transition">
                            <input type="checkbox" wire:model="imd_initiated" class="w-5 h-5 text-green-600">
                            <div>
                                <span class="font-semibold text-gray-900">Inisiasi Menyusu Dini (IMD)</span>
                                <p class="text-xs text-gray-600">Dilakukan < 1 jam setelah lahir</p>
                            </div>
                        </label>

                        <!-- Vit K -->
                        <label
                            class="flex items-center gap-3 bg-white p-4 rounded-lg border-2 border-green-200 cursor-pointer hover:bg-green-50 transition">
                            <input type="checkbox" wire:model="vit_k_given" class="w-5 h-5 text-green-600">
                            <div>
                                <span class="font-semibold text-gray-900">Injeksi Vitamin K1</span>
                                <p class="text-xs text-gray-600">Diberikan saat lahir</p>
                            </div>
                        </label>

                        <!-- Salep Mata -->
                        <label
                            class="flex items-center gap-3 bg-white p-4 rounded-lg border-2 border-green-200 cursor-pointer hover:bg-green-50 transition">
                            <input type="checkbox" wire:model="eye_ointment_given" class="w-5 h-5 text-green-600">
                            <div>
                                <span class="font-semibold text-gray-900">Salep Mata (Erythromycin)</span>
                                <p class="text-xs text-gray-600">Profilaksis infeksi mata</p>
                            </div>
                        </label>

                        <!-- HB0 -->
                        <label
                            class="flex items-center gap-3 bg-white p-4 rounded-lg border-2 border-green-200 cursor-pointer hover:bg-green-50 transition">
                            <input type="checkbox" wire:model="hb0_given" class="w-5 h-5 text-green-600">
                            <div>
                                <span class="font-semibold text-gray-900">Imunisasi Hepatitis B0 (HB0)</span>
                                <p class="text-xs text-gray-600">Otomatis buat record imunisasi jika dicentang ✨</p>
                            </div>
                        </label>
                    </div>

                    @if ($hb0_given)
                        <div class="mt-4 bg-yellow-50 border-2 border-yellow-300 rounded-lg p-3">
                            <p class="text-sm text-yellow-800">
                                <strong>ℹ️ Info:</strong> Setelah disimpan, sistem akan otomatis membuat record anak
                                baru di Modul Imunisasi dan mencatat imunisasi HB0!
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- SECTION 3: KESIMPULAN -->
        <div class="bg-white rounded-xl shadow-md border-2 border-green-300 mb-6">
            <div class="bg-gradient-to-r from-green-100 to-green-50 px-6 py-4 border-b-2 border-green-300">
                <h2 class="text-xl font-bold text-green-900 flex items-center gap-2">
                    <span>✅</span>
                    <span>Kesimpulan & Status</span>
                </h2>
                <p class="text-sm text-green-700 mt-1">Ringkasan data persalinan</p>
            </div>

            <div class="p-6 space-y-4">
                <!-- Summary Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Status Ibu -->
                    <div class="bg-pink-50 rounded-lg p-4 border-2 border-pink-300">
                        <h3 class="font-bold text-pink-900 mb-2">Status Ibu:</h3>
                        @if ($bleeding_amount && $bleeding_amount > 500)
                            <p class="text-red-700 font-semibold">⚠️ PERHATIAN KHUSUS - Perdarahan Tinggi</p>
                        @else
                            <p class="text-green-700 font-semibold">✓ Sehat</p>
                        @endif

                        @if ($delivery_method === 'Sectio Caesarea')
                            <p class="text-sm text-gray-700 mt-1">Persalinan SC - Perlu monitoring luka operasi</p>
                        @endif
                    </div>

                    <!-- Status Bayi -->
                    <div class="bg-blue-50 rounded-lg p-4 border-2 border-blue-300">
                        <h3 class="font-bold text-blue-900 mb-2">Status Bayi:</h3>
                        @if ($condition === 'Hidup')
                            @if ($apgar_score_5 && $apgar_score_5 >= 7)
                                <p class="text-green-700 font-semibold">✓ Sehat</p>
                            @else
                                <p class="text-orange-700 font-semibold">⚠️ Perlu Perhatian Khusus</p>
                            @endif
                        @elseif($condition === 'Asfiksia')
                            <p class="text-red-700 font-semibold">⚠️ RUJUK - Asfiksia</p>
                        @else
                            <p class="text-gray-700 font-semibold">† Meninggal</p>
                        @endif
                    </div>
                </div>

                <!-- Info Auto-Create -->
                @if ($condition === 'Hidup')
                    <div
                        class="bg-gradient-to-r from-purple-50 to-purple-100 border-2 border-purple-300 rounded-lg p-4">
                        <h4 class="font-bold text-purple-900 mb-2 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M11 3a1 1 0 100 2h2.586l-6.293 6.293a1 1 0 101.414 1.414L15 6.414V9a1 1 0 102 0V4a1 1 0 00-1-1h-5z">
                                </path>
                                <path
                                    d="M5 5a2 2 0 00-2 2v8a2 2 0 002 2h8a2 2 0 002-2v-3a1 1 0 10-2 0v3H5V7h3a1 1 0 000-2H5z">
                                </path>
                            </svg>
                            <span>Magic Feature - Auto Process</span>
                        </h4>
                        <ul class="text-sm text-purple-800 space-y-1 ml-7">
                            <li>✨ Sistem akan otomatis membuat <strong>Child Record</strong> baru di Modul Imunisasi
                            </li>
                            <li>✨ Status kehamilan akan diupdate ke <strong>"Lahir"</strong></li>
                            @if ($hb0_given)
                                <li>✨ Imunisasi <strong>HB0</strong> akan otomatis tercatat</li>
                            @endif
                        </ul>
                    </div>
                @endif
            </div>
        </div>

        <!-- Submit Button -->
        <div class="flex gap-4 justify-end">
            <a href="{{ route('patients.show', $pregnancy->patient_id) }}"
                class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg font-semibold hover:bg-gray-300 transition">
                Batal
            </a>

            <button type="submit"
                class="px-8 py-3 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg font-bold hover:from-green-600 hover:to-green-700 transition shadow-lg flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                <span>{{ $deliveryRecord ? 'Update Data Persalinan' : 'Simpan Data Persalinan' }}</span>
            </button>
        </div>
    </form>
</div>
