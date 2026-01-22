<?php

namespace App\Livewire;

use Livewire\Component;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class RoleManagement extends Component
{
    use AuthorizesRequests;

    public $roles;
    public $permissions;
    public $selectedRole = null;
    public $rolePermissions = [];

    // Form fields
    public $roleName = '';
    public $editMode = false;
    public $roleId = null;
    public $showModal = false;

    protected $rules = [
        'roleName' => 'required|string|max:255|unique:roles,name',
    ];

    public function mount()
    {
        $this->authorize('manage-roles');
        $this->loadData();
    }

    public function loadData()
    {
        $this->roles = Role::with('permissions')->get();
        $this->permissions = Permission::all();
    }

    public function selectRole($roleId)
    {
        $this->selectedRole = Role::with('permissions')->find($roleId);
        $this->rolePermissions = $this->selectedRole ?
            $this->selectedRole->permissions->pluck('name')->toArray() : [];
    }

    public function openCreateModal()
    {
        $this->resetForm();
        $this->showModal = true;
        $this->editMode = false;
    }

    public function openEditModal($roleId)
    {
        $role = Role::findOrFail($roleId);
        $this->roleId = $role->id;
        $this->roleName = $role->name;
        $this->editMode = true;
        $this->showModal = true;
    }

    public function saveRole()
    {
        $this->validate([
            'roleName' => $this->editMode ?
                'required|string|max:255|unique:roles,name,' . $this->roleId :
                'required|string|max:255|unique:roles,name',
        ]);

        if ($this->editMode) {
            $role = Role::findOrFail($this->roleId);
            $role->update(['name' => $this->roleName]);
            session()->flash('success', 'Role berhasil diupdate!');
        } else {
            Role::create(['name' => $this->roleName]);
            session()->flash('success', 'Role berhasil dibuat!');
        }

        $this->loadData();
        $this->closeModal();
    }

    public function deleteRole($roleId)
    {
        $role = Role::findOrFail($roleId);

        // Prevent deleting protected roles
        if (in_array($role->name, ['Admin', 'Bidan Koordinator', 'Bidan Desa'])) {
            session()->flash('error', 'Role sistem tidak bisa dihapus!');
            return;
        }

        $role->delete();
        session()->flash('success', 'Role berhasil dihapus!');
        $this->loadData();

        if ($this->selectedRole && $this->selectedRole->id === $roleId) {
            $this->selectedRole = null;
            $this->rolePermissions = [];
        }
    }

    public function updatePermissions()
    {
        if (!$this->selectedRole) {
            session()->flash('error', 'Pilih role terlebih dahulu!');
            return;
        }

        $role = Role::findOrFail($this->selectedRole->id);
        $role->syncPermissions($this->rolePermissions);

        session()->flash('success', 'Permission berhasil diupdate untuk role ' . $role->name);
        $this->loadData();
        $this->selectRole($this->selectedRole->id);
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->roleName = '';
        $this->roleId = null;
        $this->editMode = false;
    }

    public function render()
    {
        return view('livewire.role-management');
    }
}
