<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class UserRoleManagement extends Component
{
    use AuthorizesRequests;

    public $users;
    public $roles;
    public $selectedUser = null;
    public $userRoles = [];
    public $searchTerm = '';

    public function mount()
    {
        $this->authorize('manage-roles');
        $this->loadData();
    }

    public function loadData()
    {
        $query = User::with('roles');

        if ($this->searchTerm) {
            $query->where(function($q) {
                $q->where('name', 'like', '%' . $this->searchTerm . '%')
                  ->orWhere('email', 'like', '%' . $this->searchTerm . '%');
            });
        }

        $this->users = $query->get();
        $this->roles = Role::all();
    }

    public function updatedSearchTerm()
    {
        $this->loadData();
    }

    public function selectUser($userId)
    {
        $this->selectedUser = User::with('roles')->find($userId);
        $this->userRoles = $this->selectedUser ?
            $this->selectedUser->roles->pluck('name')->toArray() : [];
    }

    public function updateUserRoles()
    {
        if (!$this->selectedUser) {
            session()->flash('error', 'Pilih user terlebih dahulu!');
            return;
        }

        $user = User::findOrFail($this->selectedUser->id);
        $user->syncRoles($this->userRoles);

        session()->flash('success', 'Role berhasil diupdate untuk ' . $user->name);
        $this->loadData();
        $this->selectUser($this->selectedUser->id);
    }

    public function render()
    {
        return view('livewire.user-role-management');
    }
}
