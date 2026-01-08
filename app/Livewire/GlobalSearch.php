<?php

namespace App\Livewire;

use App\Models\Patient;
use Livewire\Component;

class GlobalSearch extends Component
{
    public $search = '';
    public $showResults = false;
    public $results = [];

    public function updatedSearch($value)
    {
        $this->search = trim($value);

        if (strlen($this->search) >= 2) {
            $this->results = Patient::query()
                ->where(function ($query) {
                    $query->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('nik', 'like', '%' . $this->search . '%')
                        ->orWhere('no_rm', 'like', '%' . $this->search . '%')
                        ->orWhere('phone', 'like', '%' . $this->search . '%');
                })
                ->limit(8)
                ->get(['id', 'no_rm', 'name', 'nik', 'phone']);

            $this->showResults = true;
        } else {
            $this->results = [];
            $this->showResults = false;
        }
    }

    public function closeResults()
    {
        $this->showResults = false;
    }

    public function render()
    {
        return view('livewire.global-search');
    }
}
