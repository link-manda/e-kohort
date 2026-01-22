<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Kelola Role User</h1>
            <p class="mt-1 text-sm text-gray-600">Assign role ke user untuk mengatur akses mereka</p>
        </div>

        <!-- Flash Messages -->
        @if (session()->has('success'))
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                {{ session('success') }}
            </div>
        @endif
        @if (session()->has('error'))
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                {{ session('error') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- User List -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Cari User</label>
                    <input type="text" wire:model.live="searchTerm" placeholder="Nama atau email..."
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>

                <h2 class="text-lg font-semibold mb-4">Daftar User ({{ $users->count() }})</h2>
                <div class="space-y-2 max-h-[600px] overflow-y-auto">
                    @forelse ($users as $user)
                        <div
                            class="flex items-center justify-between p-3 rounded-lg {{ $selectedUser && $selectedUser->id === $user->id ? 'bg-blue-50 border border-blue-300' : 'hover:bg-gray-50 border border-gray-200' }}">
                            <button wire:click="selectUser({{ $user->id }})" class="flex-1 text-left">
                                <div class="font-medium text-gray-900">{{ $user->name }}</div>
                                <div class="text-xs text-gray-500">{{ $user->email }}</div>
                                <div class="flex flex-wrap gap-1 mt-1">
                                    @foreach ($user->roles as $role)
                                        <span class="px-2 py-0.5 text-xs bg-blue-100 text-blue-800 rounded">
                                            {{ $role->name }}
                                        </span>
                                    @endforeach
                                    @if ($user->roles->isEmpty())
                                        <span class="px-2 py-0.5 text-xs bg-gray-100 text-gray-600 rounded">
                                            No roles
                                        </span>
                                    @endif
                                </div>
                            </button>
                        </div>
                    @empty
                        <div class="text-center py-4 text-gray-500">
                            Tidak ada user ditemukan
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Role Assignment -->
            <div class="lg:col-span-2 bg-white rounded-lg shadow p-6">
                @if ($selectedUser)
                    <div class="mb-4 flex justify-between items-center">
                        <div>
                            <h2 class="text-lg font-semibold">Role untuk: {{ $selectedUser->name }}</h2>
                            <p class="text-sm text-gray-600">{{ $selectedUser->email }}</p>
                        </div>
                        <button wire:click="updateUserRoles"
                            class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg">
                            Simpan Role
                        </button>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach ($roles as $role)
                            <label
                                class="flex items-start space-x-3 p-4 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50">
                                <input type="checkbox" wire:model="userRoles" value="{{ $role->name }}"
                                    class="mt-1 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                <div class="flex-1">
                                    <div class="font-medium text-gray-900">{{ $role->name }}</div>
                                    <div class="text-xs text-gray-500 mt-1">
                                        {{ $role->permissions->count() }} permissions:
                                        {{ $role->permissions->take(3)->pluck('name')->join(', ') }}
                                        @if ($role->permissions->count() > 3)
                                            <span class="text-blue-600">+{{ $role->permissions->count() - 3 }}
                                                more</span>
                                        @endif
                                    </div>
                                </div>
                            </label>
                        @endforeach
                    </div>

                    <!-- Current Permissions Preview -->
                    @if (!empty($userRoles))
                        <div class="mt-6 p-4 bg-blue-50 rounded-lg">
                            <h3 class="font-semibold text-blue-900 mb-2">Preview Permission yang akan didapat:</h3>
                            <div class="flex flex-wrap gap-2">
                                @php
                                    $allPermissions = \Spatie\Permission\Models\Role::whereIn('name', $userRoles)
                                        ->with('permissions')
                                        ->get()
                                        ->pluck('permissions')
                                        ->flatten()
                                        ->unique('name')
                                        ->pluck('name');
                                @endphp
                                @foreach ($allPermissions as $permission)
                                    <span class="px-2 py-1 text-xs bg-blue-100 text-blue-800 rounded">
                                        {{ $permission }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endif
                @else
                    <div class="text-center py-12 text-gray-500">
                        <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <p>Pilih user dari daftar untuk mengatur role</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
