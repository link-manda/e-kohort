<x-dashboard-layout>
    <!-- Breadcrumb -->
    <x-breadcrumb :items="[['label' => 'Data Pasien', 'url' => route('patients.index')], ['label' => $patient->name, 'url' => route('patients.show', $patient)], ['label' => 'Edit']]" />

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Edit Data Pasien</h1>
                <p class="text-sm text-gray-600 mt-1">Perbarui informasi pasien: {{ $patient->name }}</p>
            </div>
            <a href="{{ route('patients.show', $patient) }}"
                class="text-gray-600 hover:text-gray-800 flex items-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                <span>Kembali</span>
            </a>
        </div>
    </x-slot>

    <form action="{{ route('patients.update', $patient) }}" method="POST">
        @csrf
        @method('PATCH')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Form -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Data Identitas -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h2 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2">
                            </path>
                        </svg>
                        Data Identitas Pasien
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- NIK -->
                        <div class="md:col-span-2">
                            <label for="nik" class="block text-sm font-semibold text-gray-700 mb-2">
                                NIK
                                @if ($patient->nik)
                                    <span class="text-xs text-gray-500">(opsional)</span>
                                @else
                                    <span
                                        class="inline-block px-2 py-1 text-xs font-medium text-yellow-700 bg-yellow-100 rounded-full ml-2">Tanpa
                                        NIK</span>
                                @endif
                            </label>
                            <input type="text" id="nik" name="nik" value="{{ old('nik', $patient->nik) }}"
                                maxlength="16" placeholder="Masukkan NIK 16 digit (opsional)"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('nik') border-red-500 @enderror">
                            @error('nik')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-gray-500 mt-1">Format: 16 digit angka (kosongkan jika tidak ada)</p>
                        </div>

                        <!-- Nama Lengkap -->
                        <div class="md:col-span-2">
                            <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                                Nama Lengkap <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="name" name="name"
                                value="{{ old('name', $patient->name) }}" placeholder="Masukkan nama lengkap ibu"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('name') border-red-500 @enderror"
                                required>
                            @error('name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tanggal Lahir -->
                        <div>
                            <label for="dob" class="block text-sm font-semibold text-gray-700 mb-2">
                                Tanggal Lahir <span class="text-red-500">*</span>
                            </label>
                            <input type="date" id="dob" name="dob"
                                value="{{ old('dob', $patient->dob->format('Y-m-d')) }}" max="{{ date('Y-m-d') }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('dob') border-red-500 @enderror"
                                required>
                            @error('dob')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Golongan Darah -->
                        <div>
                            <label for="blood_type" class="block text-sm font-semibold text-gray-700 mb-2">
                                Golongan Darah <span class="text-red-500">*</span>
                            </label>
                            <select id="blood_type" name="blood_type"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('blood_type') border-red-500 @enderror"
                                required>
                                <option value="">Pilih Golongan Darah</option>
                                <option value="A"
                                    {{ old('blood_type', $patient->blood_type) == 'A' ? 'selected' : '' }}>A</option>
                                <option value="B"
                                    {{ old('blood_type', $patient->blood_type) == 'B' ? 'selected' : '' }}>B</option>
                                <option value="AB"
                                    {{ old('blood_type', $patient->blood_type) == 'AB' ? 'selected' : '' }}>AB</option>
                                <option value="O"
                                    {{ old('blood_type', $patient->blood_type) == 'O' ? 'selected' : '' }}>O</option>
                                <option value="Unknown"
                                    {{ old('blood_type', $patient->blood_type) == 'Unknown' ? 'selected' : '' }}>Tidak
                                    Tahu</option>
                            </select>
                            @error('blood_type')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- No. KK -->
                        <div>
                            <label for="no_kk" class="block text-sm font-semibold text-gray-700 mb-2">
                                Nomor KK
                            </label>
                            <input type="text" id="no_kk" name="no_kk"
                                value="{{ old('no_kk', $patient->no_kk) }}" maxlength="16"
                                placeholder="16 digit (opsional)"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('no_kk') border-red-500 @enderror">
                            @error('no_kk')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- No. BPJS -->
                        <div>
                            <label for="no_bpjs" class="block text-sm font-semibold text-gray-700 mb-2">
                                Nomor BPJS
                            </label>
                            <input type="text" id="no_bpjs" name="no_bpjs"
                                value="{{ old('no_bpjs', $patient->no_bpjs) }}" maxlength="13"
                                placeholder="13 digit (opsional)"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('no_bpjs') border-red-500 @enderror">
                            @error('no_bpjs')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Nomor Telepon -->
                        <div class="md:col-span-2">
                            <label for="phone" class="block text-sm font-semibold text-gray-700 mb-2">
                                Nomor Telepon / WhatsApp
                            </label>
                            <input type="text" id="phone" name="phone"
                                value="{{ old('phone', $patient->phone) }}" maxlength="15"
                                placeholder="Contoh: 081234567890"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('phone') border-red-500 @enderror">
                            @error('phone')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Alamat -->
                        <div class="md:col-span-2">
                            <label for="address" class="block text-sm font-semibold text-gray-700 mb-2">
                                Alamat Lengkap <span class="text-red-500">*</span>
                            </label>
                            <textarea id="address" name="address" rows="3" placeholder="Masukkan alamat lengkap domisili"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('address') border-red-500 @enderror"
                                required>{{ old('address', $patient->address) }}</textarea>
                            @error('address')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Data Suami -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h2 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        Data Suami
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Nama Suami -->
                        <div class="md:col-span-2">
                            <label for="husband_name" class="block text-sm font-semibold text-gray-700 mb-2">
                                Nama Suami
                            </label>
                            <input type="text" id="husband_name" name="husband_name"
                                value="{{ old('husband_name', $patient->husband_name) }}"
                                placeholder="Masukkan nama lengkap suami"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('husband_name') border-red-500 @enderror">
                            @error('husband_name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- NIK Suami -->
                        <div>
                            <label for="husband_nik" class="block text-sm font-semibold text-gray-700 mb-2">
                                NIK Suami
                            </label>
                            <input type="text" id="husband_nik" name="husband_nik"
                                value="{{ old('husband_nik', $patient->husband_nik) }}" maxlength="16"
                                placeholder="16 digit (opsional)"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('husband_nik') border-red-500 @enderror">
                            @error('husband_nik')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Pekerjaan Suami -->
                        <div>
                            <label for="husband_job" class="block text-sm font-semibold text-gray-700 mb-2">
                                Pekerjaan Suami
                            </label>
                            <input type="text" id="husband_job" name="husband_job"
                                value="{{ old('husband_job', $patient->husband_job) }}"
                                placeholder="Contoh: Petani, Wiraswasta, PNS"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('husband_job') border-red-500 @enderror">
                            @error('husband_job')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar Info & Actions -->
            <div class="space-y-6">
                <!-- Patient Info Card -->
                <div class="bg-gradient-to-br from-blue-50 to-purple-50 border border-blue-200 rounded-xl p-6">
                    <div class="text-center mb-4">
                        <div
                            class="w-20 h-20 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white text-3xl font-bold mx-auto mb-3">
                            {{ strtoupper(substr($patient->name, 0, 1)) }}
                        </div>
                        <h3 class="font-bold text-gray-800 text-lg">{{ $patient->name }}</h3>
                        <p class="text-sm text-gray-600">{{ $patient->age }} tahun</p>
                    </div>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">NIK:</span>
                            <span class="font-mono font-semibold">
                                @if ($patient->nik)
                                    {{ $patient->nik }}
                                @else
                                    <span
                                        class="text-yellow-600 text-xs font-medium px-2 py-1 bg-yellow-100 rounded-full">Tanpa
                                        NIK</span>
                                @endif
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Gol. Darah:</span>
                            <span class="font-semibold">{{ $patient->blood_type }}</span>
                        </div>
                        @if ($patient->activePregnancy)
                            <div class="mt-3 pt-3 border-t border-blue-200">
                                <span
                                    class="inline-block px-3 py-1 text-xs font-semibold text-green-700 bg-green-100 rounded-full">
                                    Kehamilan Aktif
                                </span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <button type="submit"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-3 rounded-lg transition-colors flex items-center justify-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                            </path>
                        </svg>
                        <span>Simpan Perubahan</span>
                    </button>
                    <a href="{{ route('patients.show', $patient) }}"
                        class="mt-3 w-full bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold px-6 py-3 rounded-lg transition-colors flex items-center justify-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        <span>Batal</span>
                    </a>
                </div>
            </div>
        </div>
    </form>
</x-dashboard-layout>
