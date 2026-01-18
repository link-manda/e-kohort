<?php

namespace App\Livewire\Admin;

use App\Models\Vaccine;
use Livewire\Component;
use Livewire\WithPagination;

class VaccineManagement extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = 'all'; // all, active, inactive, deleted
    public $perPage = 20;

    public $showModal = false;
    public $editing = null; // Vaccine model

    public $code, $name, $description, $min_age_months = 0, $max_age_months = 24, $sort_order = 0, $is_active = true;

    protected $rules = [
        'code' => 'required|string|max:20|unique:vaccines,code',
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'min_age_months' => 'required|integer|min:0|max:240',
        'max_age_months' => 'required|integer|min:0|max:240|gte:min_age_months',
        'sort_order' => 'nullable|integer',
        'is_active' => 'boolean',
    ];

    protected $messages = [
        'code.required' => 'Kode vaksin wajib diisi',
        'code.unique' => 'Kode vaksin sudah digunakan',
        'name.required' => 'Nama vaksin wajib diisi',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->search = '';
        $this->statusFilter = 'all';
        $this->resetPage();
    }

    public function create()
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function edit($id)
    {
        $v = Vaccine::withTrashed()->findOrFail($id);
        $this->editing = $v;
        $this->code = $v->code;
        $this->name = $v->name;
        $this->description = $v->description;
        $this->min_age_months = $v->min_age_months;
        $this->max_age_months = $v->max_age_months;
        $this->sort_order = $v->sort_order;
        $this->is_active = $v->is_active;
        $this->showModal = true;

        // Adjust unique rule for update
        $this->rules['code'] = 'required|string|max:20|unique:vaccines,code,' . $v->id;
    }

    public function save()
    {
        $this->validate();

        $data = [
            'code' => strtoupper($this->code),
            'name' => $this->name,
            'description' => $this->description,
            'min_age_months' => $this->min_age_months,
            'max_age_months' => $this->max_age_months,
            'sort_order' => $this->sort_order ?: 0,
            'is_active' => (bool) $this->is_active,
        ];

        if ($this->editing) {
            $this->editing->update($data);
            session()->flash('success', 'Vaksin berhasil diperbarui');
        } else {
            Vaccine::create($data);
            session()->flash('success', 'Vaksin berhasil ditambahkan');
        }

        $this->showModal = false;
        $this->resetForm();
    }

    public function confirmDelete($id)
    {
        $v = Vaccine::findOrFail($id);
        $v->delete();
        session()->flash('success', 'Vaksin dihapus (soft delete)');
    }

    public function restore($id)
    {
        $v = Vaccine::withTrashed()->findOrFail($id);
        $v->restore();
        session()->flash('success', 'Vaksin berhasil direstore');
    }

    public function toggleActive($id)
    {
        $v = Vaccine::findOrFail($id);
        $v->is_active = !$v->is_active;
        $v->save();
        session()->flash('success', 'Status vaksin diperbarui');
    }

    private function resetForm()
    {
        $this->editing = null;
        $this->code = '';
        $this->name = '';
        $this->description = '';
        $this->min_age_months = 0;
        $this->max_age_months = 24;
        $this->sort_order = 0;
        $this->is_active = true;

        // reset rule
        $this->rules['code'] = 'required|string|max:20|unique:vaccines,code';
    }

    public function render()
    {
        $query = Vaccine::withTrashed()->orderBy('sort_order');

        if ($this->search) {
            $term = "%{$this->search}%";
            $query->where(function($q) use ($term) {
                $q->where('name', 'like', $term)
                  ->orWhere('code', 'like', $term)
                  ->orWhere('description', 'like', $term);
            });
        }

        // Filter by status
        if ($this->statusFilter === 'active') {
            $query->where('is_active', true)->whereNull('deleted_at');
        } elseif ($this->statusFilter === 'inactive') {
            $query->where('is_active', false)->whereNull('deleted_at');
        } elseif ($this->statusFilter === 'deleted') {
            $query->whereNotNull('deleted_at');
        }

        $vaccines = $query->paginate($this->perPage);

        // Calculate statistics
        $totalVaccines = Vaccine::withTrashed()->count();
        $activeVaccines = Vaccine::where('is_active', true)->count();
        $inactiveVaccines = Vaccine::where('is_active', false)->count();
        $deletedVaccines = Vaccine::onlyTrashed()->count();

        return view('livewire.admin.vaccine-management', [
            'vaccines' => $vaccines,
            'stats' => [
                'total' => $totalVaccines,
                'active' => $activeVaccines,
                'inactive' => $inactiveVaccines,
                'deleted' => $deletedVaccines,
            ]
        ]);
    }
}
