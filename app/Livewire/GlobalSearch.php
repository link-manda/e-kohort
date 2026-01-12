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
            $keyword = $this->search;

            // SMART SEARCH LOGIC dengan prioritas
            $this->results = Patient::query()
                ->where(function ($query) use ($keyword) {
                    // Prioritas 1: Cek pola RM (case insensitive)
                    if (stripos($keyword, 'RM-') !== false || stripos($keyword, 'rm-') !== false) {
                        $query->where('no_rm', 'like', '%' . $keyword . '%');
                    }
                    // Prioritas 2: Cek numerik (phone atau nik)
                    elseif (is_numeric($keyword)) {
                        // Prioritas utama: Phone
                        $query->where('phone', 'like', '%' . $keyword . '%')
                            // Prioritas kedua: NIK
                            ->orWhere('nik', 'like', '%' . $keyword . '%');
                    }
                    // Prioritas 3: Default string (nama)
                    else {
                        $query->where('name', 'like', '%' . $keyword . '%');
                    }
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
