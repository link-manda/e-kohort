<?php

namespace App\Livewire;

use App\Models\Pregnancy;
use Livewire\Component;

class DeliveryEntry extends Component
{
    public $pregnancy;

    // Form fields
    public $delivery_date = '';
    public $delivery_time = '';
    public $delivery_method = '';
    public $birth_attendant = '';
    public $place_of_birth = '';
    public $outcome = '';
    public $baby_gender = '';
    public $complications = '';

    // UI state
    public $showSuccess = false;

    protected function rules()
    {
        return [
            'delivery_date' => 'required|date',
            'delivery_time' => 'required',
            'delivery_method' => 'required|in:Normal,Caesar/Sectio,Vakum',
            'birth_attendant' => 'required|string|max:255',
            'place_of_birth' => 'required|string|max:255',
            'outcome' => 'required|in:Hidup,Meninggal,Abortus',
            'baby_gender' => 'required_if:outcome,Hidup|nullable|in:L,P',
            'complications' => 'nullable|string',
        ];
    }

    protected $messages = [
        'delivery_date.required' => 'Tanggal lahir wajib diisi',
        'delivery_time.required' => 'Waktu lahir wajib diisi',
        'delivery_method.required' => 'Cara persalinan wajib dipilih',
        'birth_attendant.required' => 'Penolong persalinan wajib diisi',
        'place_of_birth.required' => 'Tempat lahir wajib diisi',
        'outcome.required' => 'Kondisi bayi wajib dipilih',
        'baby_gender.required_if' => 'Jenis kelamin bayi wajib dipilih jika bayi hidup',
    ];

    public function mount(Pregnancy $pregnancy)
    {
        $this->pregnancy = $pregnancy;

        // Check if already delivered
        if ($this->pregnancy->status === 'Lahir' && $this->pregnancy->delivery_date) {
            // Load existing data
            $this->delivery_date = $this->pregnancy->delivery_date->format('Y-m-d');
            $this->delivery_time = $this->pregnancy->delivery_date->format('H:i');
            $this->delivery_method = $this->pregnancy->delivery_method;
            $this->birth_attendant = $this->pregnancy->birth_attendant;
            $this->place_of_birth = $this->pregnancy->place_of_birth;
            $this->outcome = $this->pregnancy->outcome;
            $this->baby_gender = $this->pregnancy->baby_gender;
            $this->complications = $this->pregnancy->complications;
        } else {
            // Set default time to now
            $this->delivery_time = now()->format('H:i');
        }
    }

    public function save()
    {
        $this->validate();

        // Combine date and time
        $deliveryDateTime = $this->delivery_date . ' ' . $this->delivery_time;

        // Update pregnancy data
        $this->pregnancy->update([
            'delivery_date' => $deliveryDateTime,
            'delivery_method' => $this->delivery_method,
            'birth_attendant' => $this->birth_attendant,
            'place_of_birth' => $this->place_of_birth,
            'outcome' => $this->outcome,
            'baby_gender' => $this->outcome === 'Hidup' ? $this->baby_gender : null,
            'complications' => $this->complications,
            'status' => 'Lahir', // Update status to Lahir
        ]);

        $this->showSuccess = true;

        // Redirect to postnatal page after 2 seconds
        $this->dispatch('delivery-saved');
    }

    public function render()
    {
        return view('livewire.delivery-entry');
    }
}
