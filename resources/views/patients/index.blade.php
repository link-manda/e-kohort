<x-dashboard-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Data Pasien</h1>
                <p class="text-sm text-gray-600 mt-1">Kelola data ibu hamil yang terdaftar di klinik</p>
            </div>
            <a href="{{ route('patients.create') }}"
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                <span>Tambah Pasien</span>
            </a>
        </div>
    </x-slot>

    <!-- Search & Filter -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
        <form method="GET" action="{{ route('patients.index') }}" class="flex flex-col md:flex-row gap-4">
            <!-- Search Input -->
            <div class="flex-1">
                <div class="relative">
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Cari berdasarkan nama, NIK, atau telepon..."
                        class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <svg class="w-5 h-5 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
            </div>

            <!-- Blood Type Filter -->
            <div class="w-full md:w-48">
                <select name="blood_type"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Semua Golongan Darah</option>
                    <option value="A" {{ request('blood_type') == 'A' ? 'selected' : '' }}>A</option>
                    <option value="B" {{ request('blood_type') == 'B' ? 'selected' : '' }}>B</option>
                    <option value="AB" {{ request('blood_type') == 'AB' ? 'selected' : '' }}>AB</option>
                    <option value="O" {{ request('blood_type') == 'O' ? 'selected' : '' }}>O</option>
                </select>
            </div>

            <!-- Buttons -->
            <div class="flex gap-2">
                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition-colors">
                    Cari
                </button>
                <a href="{{ route('patients.index') }}"
                    class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-6 py-2 rounded-lg transition-colors">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Patients Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        @if ($patients->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th
                                class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Pasien</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                NIK</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Usia</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Gol. Darah</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Status</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach ($patients as $patient)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center space-x-3">
                                        <div
                                            class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-semibold">
                                            {{ strtoupper(substr($patient->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <p class="font-semibold text-gray-800">{{ $patient->name }}</p>
                                            <p class="text-sm text-gray-600">{{ $patient->phone ?? '-' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-700 font-mono">{{ $patient->nik ?? '-' }}</td>
                                <td class="px-6 py-4 text-sm text-gray-700">{{ $patient->age }} tahun</td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 text-xs font-semibold text-blue-700 bg-blue-100 rounded">
                                        {{ $patient->blood_type }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    @if ($patient->activePregnancy)
                                        <span
                                            class="px-2 py-1 text-xs font-semibold text-green-700 bg-green-100 rounded">
                                            Hamil Aktif
                                        </span>
                                    @else
                                        <span class="px-2 py-1 text-xs font-semibold text-gray-600 bg-gray-100 rounded">
                                            Tidak Aktif
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ route('patients.show', $patient) }}"
                                            class="text-blue-600 hover:text-blue-800 p-1 rounded hover:bg-blue-50 transition-colors"
                                            title="Lihat Detail">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                </path>
                                            </svg>
                                        </a>
                                        <a href="{{ route('patients.edit', $patient) }}"
                                            class="text-green-600 hover:text-green-800 p-1 rounded hover:bg-green-50 transition-colors"
                                            title="Edit">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                </path>
                                            </svg>
                                        </a>
                                        <form action="{{ route('patients.destroy', $patient) }}" method="POST"
                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus data pasien ini?')"
                                            class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="text-red-600 hover:text-red-800 p-1 rounded hover:bg-red-50 transition-colors"
                                                title="Hapus">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                    </path>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $patients->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                    </path>
                </svg>
                <p class="text-gray-500 text-lg mb-2">Tidak ada data pasien</p>
                <p class="text-gray-400 text-sm mb-4">Mulai dengan menambahkan pasien baru</p>
                <a href="{{ route('patients.create') }}"
                    class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition-colors">
                    Tambah Pasien Pertama
                </a>
            </div>
        @endif
    </div>

    <!-- Success Message -->
    @if (session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
            class="fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg flex items-center space-x-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif
</x-dashboard-layout>
