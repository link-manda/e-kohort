<?php

namespace App\Livewire\Admin;

use App\Models\Icd10Code;
use Livewire\Component;
use Livewire\WithPagination;

class Icd10Management extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = 'all'; // all, active, inactive, deleted
    public $categoryFilter = 'all'; // all, immunization, other
    public $perPage = 20;

    public $showModal = false;
    public $editing = null; // Icd10Code model

    public $code, $name, $description, $category = 'immunization', $keywords = '', $is_active = true;

    protected $rules = [
        'code' => 'required|string|max:10|unique:icd10_codes,code',
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'category' => 'required|string|max:50',
        'keywords' => 'nullable|string',
        'is_active' => 'boolean',
    ];

    protected $messages = [
        'code.required' => 'Kode ICD wajib diisi',
        'code.unique' => 'Kode ICD sudah ada',
        'name.required' => 'Nama diagnosa wajib diisi',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function updatingCategoryFilter()
    {
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->search = '';
        $this->statusFilter = 'all';
        $this->categoryFilter = 'all';
        $this->resetPage();
    }

    public function create()
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function edit($id)
    {
        $i = Icd10Code::withTrashed()->findOrFail($id);
        $this->editing = $i;
        $this->code = $i->code;
        $this->name = $i->name;
        $this->description = $i->description;
        $this->category = $i->category;
        $this->keywords = is_array($i->keywords) ? implode(',', $i->keywords) : ($i->keywords ?? '');
        $this->is_active = $i->is_active;

        $this->showModal = true;
        $this->rules['code'] = 'required|string|max:10|unique:icd10_codes,code,' . $i->id;
    }

    public function save()
    {
        $this->validate();

        $data = [
            'code' => strtoupper($this->code),
            'name' => $this->name,
            'description' => $this->description,
            'category' => $this->category,
            'keywords' => $this->keywords ? explode(',', str_replace(', ', ',', $this->keywords)) : null,
            'is_active' => (bool) $this->is_active,
        ];

        if ($this->editing) {
            $this->editing->update($data);
            session()->flash('success', 'Kode ICD berhasil diperbarui');
        } else {
            Icd10Code::create($data);
            session()->flash('success', 'Kode ICD berhasil ditambahkan');
        }

        $this->showModal = false;
        $this->resetForm();
    }

    public function confirmDelete($id)
    {
        $i = Icd10Code::findOrFail($id);
        $i->delete();
        session()->flash('success', 'Kode ICD dihapus (soft delete)');
    }

    public function restore($id)
    {
        $i = Icd10Code::withTrashed()->findOrFail($id);
        $i->restore();
        session()->flash('success', 'Kode ICD berhasil direstore');
    }

    public function toggleActive($id)
    {
        $i = Icd10Code::findOrFail($id);
        $i->is_active = !$i->is_active;
        $i->save();
        session()->flash('success', 'Status kode ICD diperbarui');
    }

    private function resetForm()
    {
        $this->editing = null;
        $this->code = '';
        $this->name = '';
        $this->description = '';
        $this->category = 'immunization';
        $this->keywords = '';
        $this->is_active = true;

        $this->rules['code'] = 'required|string|max:10|unique:icd10_codes,code';
    }

    public function render()
    {
        $query = Icd10Code::withTrashed()->orderBy('code');

        if ($this->search) {
            $term = "%{$this->search}%";
            $query->where(function($q) use ($term) {
                $q->where('code', 'like', $term)
                  ->orWhere('name', 'like', $term)
                  ->orWhere('description', 'like', $term)
                  ->orWhereRaw('LOWER(JSON_EXTRACT(keywords, "$")) LIKE ?', [strtolower($term)]);
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

        // Filter by category
        if ($this->categoryFilter === 'immunization') {
            $query->where('category', 'immunization');
        } elseif ($this->categoryFilter === 'other') {
            $query->where('category', '!=', 'immunization');
        }

        $codes = $query->paginate($this->perPage);

        // Calculate statistics
        $totalCodes = Icd10Code::withTrashed()->count();
        $activeCodes = Icd10Code::where('is_active', true)->count();
        $inactiveCodes = Icd10Code::where('is_active', false)->count();
        $deletedCodes = Icd10Code::onlyTrashed()->count();

        return view('livewire.admin.icd10-management', [
            'codes' => $codes,
            'stats' => [
                'total' => $totalCodes,
                'active' => $activeCodes,
                'inactive' => $inactiveCodes,
                'deleted' => $deletedCodes,
            ]
        ]);
    }
}
