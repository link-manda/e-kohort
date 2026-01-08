<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserManagement extends Component
{
    use WithPagination;

    public $search = '';
    public $roleFilter = 'all';
    public $statusFilter = 'all';

    // Form properties
    public $showModal = false;
    public $editMode = false;
    public $userId;
    public $name;
    public $email;
    public $password;
    public $password_confirmation;
    public $selectedRole;
    public $isActive = true;

    // Delete confirmation
    public $showDeleteModal = false;
    public $userToDelete;

    protected $queryString = ['search', 'roleFilter', 'statusFilter'];

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($this->userId)
            ],
            'password' => $this->editMode ? 'nullable|min:8|confirmed' : 'required|min:8|confirmed',
            'selectedRole' => 'required|exists:roles,name',
            'isActive' => 'boolean',
        ];
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingRoleFilter()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function openModal()
    {
        $this->resetForm();
        $this->editMode = false;
        $this->showModal = true;
    }

    public function editUser($userId)
    {
        $user = User::with('roles')->findOrFail($userId);

        $this->userId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->selectedRole = $user->roles->first()?->name;
        $this->isActive = $user->is_active;

        $this->editMode = true;
        $this->showModal = true;
    }

    public function saveUser()
    {
        $this->validate();

        try {
            if ($this->editMode) {
                $user = User::findOrFail($this->userId);

                $user->update([
                    'name' => $this->name,
                    'email' => $this->email,
                    'is_active' => $this->isActive,
                ]);

                // Update password if provided
                if ($this->password) {
                    $user->update([
                        'password' => Hash::make($this->password),
                    ]);
                }

                // Sync role
                $user->syncRoles([$this->selectedRole]);

                session()->flash('message', 'User berhasil diupdate!');
            } else {
                $user = User::create([
                    'name' => $this->name,
                    'email' => $this->email,
                    'password' => Hash::make($this->password),
                    'is_active' => $this->isActive,
                ]);

                // Assign role
                $user->assignRole($this->selectedRole);

                session()->flash('message', 'User berhasil ditambahkan!');
            }

            $this->closeModal();
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function confirmDelete($userId)
    {
        $this->userToDelete = User::findOrFail($userId);
        $this->showDeleteModal = true;
    }

    public function deleteUser()
    {
        if ($this->userToDelete) {
            // Prevent deleting own account
            if ($this->userToDelete->id === auth()->id()) {
                session()->flash('error', 'Anda tidak dapat menghapus akun sendiri!');
                $this->cancelDelete();
                return;
            }

            $this->userToDelete->delete();
            session()->flash('message', 'User berhasil dihapus!');

            $this->cancelDelete();
        }
    }

    public function toggleStatus($userId)
    {
        $user = User::findOrFail($userId);

        // Prevent deactivating own account
        if ($user->id === auth()->id()) {
            session()->flash('error', 'Anda tidak dapat menonaktifkan akun sendiri!');
            return;
        }

        $user->update([
            'is_active' => !$user->is_active
        ]);

        $status = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';
        session()->flash('message', "User berhasil {$status}!");
    }

    public function resetForm()
    {
        $this->reset([
            'userId',
            'name',
            'email',
            'password',
            'password_confirmation',
            'selectedRole',
            'isActive',
        ]);
        $this->isActive = true;
        $this->resetErrorBag();
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function cancelDelete()
    {
        $this->showDeleteModal = false;
        $this->userToDelete = null;
    }

    public function render()
    {
        $query = User::with('roles')->orderBy('created_at', 'desc');

        // Search filter
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('email', 'like', '%' . $this->search . '%');
            });
        }

        // Role filter
        if ($this->roleFilter !== 'all') {
            $query->role($this->roleFilter);
        }

        // Status filter
        if ($this->statusFilter === 'active') {
            $query->where('is_active', true);
        } elseif ($this->statusFilter === 'inactive') {
            $query->where('is_active', false);
        }

        $users = $query->paginate(10);
        $roles = Role::all();

        return view('livewire.user-management', [
            'users' => $users,
            'roles' => $roles,
        ])->layout('layouts.dashboard');
    }
}
