<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6 flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Kelola Role & Permission</h1>
                <p class="mt-1 text-sm text-gray-600">Atur role dan permission untuk sistem RBAC</p>
            </div>
            <button wire:click="openCreateModal"
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                <span>Tambah Role</span>
            </button>
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
            <!-- Role List -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold mb-4">Daftar Role</h2>
                <div class="space-y-2">
                    @foreach ($roles as $role)
                        <div
                            class="flex items-center justify-between p-3 rounded-lg {{ $selectedRole && $selectedRole->id === $role->id ? 'bg-blue-50 border border-blue-300' : 'hover:bg-gray-50 border border-gray-200' }}">
                            <button wire:click="selectRole({{ $role->id }})" class="flex-1 text-left">
                                <div class="font-medium text-gray-900">{{ $role->name }}</div>
                                <div class="text-xs text-gray-500">{{ $role->permissions->count() }} permissions</div>
                            </button>
                            <div class="flex space-x-1">
                                <button wire:click="openEditModal({{ $role->id }})"
                                    class="p-2 text-blue-600 hover:bg-blue-50 rounded">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                        </path>
                                    </svg>
                                </button>
                                @if (!in_array($role->name, ['Admin', 'Bidan Koordinator', 'Bidan Desa']))
                                    <button wire:click="deleteRole({{ $role->id }})"
                                        wire:confirm="Yakin ingin menghapus role {{ $role->name }}?"
                                        class="p-2 text-red-600 hover:bg-red-50 rounded">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                            </path>
                                        </svg>
                                    </button>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Permission Assignment -->
            <div class="lg:col-span-2 bg-white rounded-lg shadow p-6">
                @if ($selectedRole)
                    <div class="mb-4 flex justify-between items-center">
                        <div>
                            <h2 class="text-lg font-semibold">Permission untuk: {{ $selectedRole->name }}</h2>
                            <p class="text-sm text-gray-600">Pilih permission yang ingin diberikan ke role ini</p>
                        </div>
                        <button wire:click="updatePermissions"
                            class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg">
                            Simpan Permission
                        </button>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @php
                            $groupedPermissions = $permissions->groupBy(function ($permission) {
                                return explode('-', $permission->name)[1] ?? 'other';
                            });
                        @endphp

                        @foreach ($groupedPermissions as $group => $perms)
                            <div class="border border-gray-200 rounded-lg p-4">
                                <h3 class="font-semibold text-gray-800 mb-3 capitalize">{{ ucfirst($group) }}</h3>
                                <div class="space-y-2">
                                    @foreach ($perms as $permission)
                                        <label
                                            class="flex items-center space-x-2 cursor-pointer hover:bg-gray-50 p-2 rounded">
                                            <input type="checkbox" wire:model="rolePermissions"
                                                value="{{ $permission->name }}"
                                                class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                            <span class="text-sm text-gray-700">{{ $permission->name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12 text-gray-500">
                        <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                            </path>
                        </svg>
                        <p>Pilih role dari daftar untuk mengatur permission</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Create/Edit Modal -->
    @if ($showModal)
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold">{{ $editMode ? 'Edit Role' : 'Tambah Role Baru' }}</h3>
                </div>
                <div class="px-6 py-4">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nama Role</label>
                        <input type="text" wire:model="roleName"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Contoh: Dokter, Apoteker">
                        @error('roleName')
                            <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="px-6 py-4 bg-gray-50 flex justify-end space-x-3">
                    <button wire:click="closeModal"
                        class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-100">
                        Batal
                    </button>
                    <button wire:click="saveRole" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        {{ $editMode ? 'Update' : 'Simpan' }}
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
