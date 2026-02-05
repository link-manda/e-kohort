<div class="space-y-6">
    <!-- Flash Messages -->
    @if (session()->has('success'))
        <div class="bg-green-50 border-l-4 border-green-400 p-4 rounded-md">
            <div class="flex">
                <div class="flex-shrink-0">
                    <x-heroicon-o-check-circle class="h-5 w-5 text-green-400" />
                </div>
                <div class="ml-3">
                    <p class="text-sm text-green-700">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="bg-red-50 border-l-4 border-red-400 p-4 rounded-md">
            <div class="flex">
                <div class="flex-shrink-0">
                    <x-heroicon-o-x-circle class="h-5 w-5 text-red-400" />
                </div>
                <div class="ml-3">
                    <p class="text-sm text-red-700">{{ session('error') }}</p>
                </div>
            </div>
        </div>
    @endif

    @if (session()->has('warning'))
        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-md">
            <div class="flex">
                <div class="flex-shrink-0">
                    <x-heroicon-o-exclamation-triangle class="h-5 w-5 text-yellow-400" />
                </div>
                <div class="ml-3">
                    <p class="text-sm text-yellow-700">{{ session('warning') }}</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Header -->
    <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
        <h2 class="text-xl font-bold text-gray-800 mb-2">Pendaftaran Layanan (Kunjungan)</h2>
        <p class="text-gray-600">Cari pasien untuk mendaftarkan kunjungan baru atau buat pasien baru.</p>
    </div>

    <!-- Search & Selection -->
    <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
        @if (!$selectedPatient)
            <div class="space-y-4">
                <div class="relative">
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Cari Pasien</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <x-heroicon-o-magnifying-glass class="h-5 w-5 text-gray-400" />
                        </div>
                        <input wire:model.live.debounce.300ms="search" type="text" id="search"
                            class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:border-blue-300 focus:ring focus:ring-blue-200 sm:text-sm"
                            placeholder="Cari Nama, NIK, atau No. RM..." autofocus>
                    </div>
                </div>

                @if (!empty($results) && count($results) > 0)
                    <div class="border border-gray-200 rounded-md overflow-hidden">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        No. RM</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Nama</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        NIK</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Alamat</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($results as $patient)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ $patient->no_rm }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                            {{ $patient->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $patient->nik }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ Str::limit($patient->address, 30) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <button wire:click="selectPatient({{ $patient->id }})"
                                                class="text-blue-600 hover:text-blue-900">Pilih</button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @elseif(strlen($search) >= 2)
                    <div class="text-center py-4 text-gray-500">
                        Pasien tidak ditemukan. <a href="{{ route('patients.create') }}"
                            class="text-blue-600 font-medium hover:underline">Buat Pasien Baru</a>
                    </div>
                @endif

                <div class="mt-4 pt-4 border-t border-gray-100 flex justify-end">
                    <a href="{{ route('patients.create') }}"
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <x-heroicon-o-user-plus class="h-5 w-5 mr-2" />
                        Pasien Baru
                    </a>
                </div>
            </div>
        @else
            <!-- Selected Patient View -->
            <div>
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <h3 class="text-lg font-medium leading-6 text-gray-900">Pasien Terpilih</h3>
                        <p class="mt-1 max-w-2xl text-sm text-gray-500">Silahkan pilih layanan tujuan untuk pasien ini.
                        </p>
                    </div>
                    <button wire:click="resetSelection"
                        class="text-sm text-gray-500 hover:text-gray-700 flex items-center">
                        <x-heroicon-o-x-mark class="h-5 w-5 mr-1" />
                        Batal / Ganti Pasien
                    </button>
                </div>

                <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <x-heroicon-o-user class="h-5 w-5 text-blue-400" />
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-blue-800">{{ $selectedPatient->name }}
                                ({{ $selectedPatient->no_rm }})</h3>
                            <div class="mt-2 text-sm text-blue-700 grid grid-cols-1 md:grid-cols-2 gap-2">
                                <p><span class="font-medium">NIK:</span> {{ $selectedPatient->nik ?? '-' }}</p>
                                <p><span class="font-medium">Tgl Lahir:</span>
                                    {{ $selectedPatient->dob ? $selectedPatient->dob->format('d/m/Y') : '-' }}
                                    ({{ $selectedPatient->age }} th)</p>
                                <p><span class="font-medium">Alamat:</span> {{ $selectedPatient->address }}</p>
                                <p><span class="font-medium">JK:</span>
                                    {{ $selectedPatient->gender == 'L' ? 'Laki-laki' : 'Perempuan' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <!-- Poli Umum -->
                    <button wire:click="selectService('general')"
                        class="flex flex-col items-center justify-center p-6 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 hover:border-blue-300 transition-all group">
                        <div class="p-3 bg-blue-100 rounded-full group-hover:bg-blue-200 mb-3">
                            <x-heroicon-o-clipboard-document-check class="h-8 w-8 text-blue-600" />
                        </div>
                        <h4 class="font-semibold text-gray-900">Poli Umum</h4>
                        <p class="text-sm text-gray-500 text-center mt-1">Sakit umum, cek kesehatan, rujukan.</p>
                    </button>

                    <!-- Poli KIA (ANC) -->
                    <button wire:click="selectService('kia')"
                        class="flex flex-col items-center justify-center p-6 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 hover:border-pink-300 transition-all group">
                        <div class="p-3 bg-pink-100 rounded-full group-hover:bg-pink-200 mb-3">
                            <x-heroicon-o-heart class="h-8 w-8 text-pink-600" />
                        </div>
                        <h4 class="font-semibold text-gray-900">Poli KIA (Ibu Hamil)</h4>
                        <p class="text-sm text-gray-500 text-center mt-1">Pemeriksaan kehamilan (ANC).</p>
                    </button>

                    <!-- Poli KB -->
                    <button wire:click="selectService('kb')"
                        class="flex flex-col items-center justify-center p-6 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 hover:border-purple-300 transition-all group">
                        <div class="p-3 bg-purple-100 rounded-full group-hover:bg-purple-200 mb-3">
                            <x-heroicon-o-check-circle class="h-8 w-8 text-purple-600" />
                        </div>
                        <h4 class="font-semibold text-gray-900">Poli KB</h4>
                        <p class="text-sm text-gray-500 text-center mt-1">Layanan Keluarga Berencana.</p>
                    </button>

                    <!-- Poli Anak / Imunisasi -->
                    <button wire:click="selectService('child')"
                        class="flex flex-col items-center justify-center p-6 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 hover:border-green-300 transition-all group">
                        <div class="p-3 bg-green-100 rounded-full group-hover:bg-green-200 mb-3">
                            <x-heroicon-o-face-smile class="h-8 w-8 text-green-600" />
                        </div>
                        <h4 class="font-semibold text-gray-900">Poli Anak / Imunisasi</h4>
                        <p class="text-sm text-gray-500 text-center mt-1">Imunisasi, tumbuh kembang anak.</p>
                    </button>

                    <!-- Poli Nifas -->
                    <button wire:click="selectService('nifas')"
                        class="flex flex-col items-center justify-center p-6 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 hover:border-orange-300 transition-all group">
                        <div class="p-3 bg-orange-100 rounded-full group-hover:bg-orange-200 mb-3">
                            <x-heroicon-o-users class="h-8 w-8 text-orange-600" />
                        </div>
                        <h4 class="font-semibold text-gray-900">Poli Nifas</h4>
                        <p class="text-sm text-gray-500 text-center mt-1">Pemeriksaan pasca salin (PNC).</p>
                    </button>
                </div>
            </div>
        @endif
    </div>

    <!-- Nifas Warning Modal - REMOVED: No longer needed. PostnatalEntry will handle external birth modal -->
</div>
