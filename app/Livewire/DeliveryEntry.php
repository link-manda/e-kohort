<?php

namespace App\Livewire;

use App\Models\Pregnancy;
use App\Models\DeliveryRecord;
use Livewire\Component;
use Livewire\Attributes\Validate;

class DeliveryEntry extends Component
{
    public $pregnancy;
    public $deliveryRecord; // For editing existing record

    // Data Persalinan Umum
    #[Validate('required|date')]
    public $delivery_date = '';

    #[Validate('required')]
    public $delivery_time = '';

    #[Validate('required|integer|min:20|max:44')]
    public $gestational_age = '';

    #[Validate('required|string|max:255')]
    public $birth_attendant = '';

    #[Validate('required|string|max:255')]
    public $place_of_birth = '';

    // Data Ibu - Kala I & II
    #[Validate('nullable|string')]
    public $duration_first_stage = '';

    #[Validate('nullable|string')]
    public $duration_second_stage = '';

    #[Validate('required|in:Spontan Belakang Kepala,Sungsang,Vakum,Sectio Caesarea')]
    public $delivery_method = '';

    // Data Ibu - Kala III & IV
    #[Validate('required|in:Spontan,Manual,Sisa')]
    public $placenta_delivery = 'Spontan';

    // Manajemen Aktif Kala 3 (AMTSL)
    public $oxytocin_injection = false;
    public $controlled_cord_traction = false;
    public $uterine_massage = false;

    #[Validate('required|in:Utuh,Derajat 1,Derajat 2,Derajat 3,Derajat 4,Episiotomi')]
    public $perineum_rupture = 'Utuh';

    #[Validate('nullable|integer|min:0')]
    public $bleeding_amount = '';

    #[Validate('nullable|string')]
    public $blood_pressure = '';

    #[Validate('nullable|string')]
    public $postpartum_monitoring_2h = '';

    #[Validate('nullable|string')]
    public $complications = '';

    #[Validate('nullable|numeric|min:0')]
    public $service_fee = '';

    // Data Bayi - Identitas
    #[Validate('nullable|string|max:255')]
    public $baby_name = '';

    #[Validate('required|in:L,P')]
    public $gender = '';

    // Data Bayi - Antropometri
    #[Validate('required|numeric|min:500|max:6000')]
    public $birth_weight = '';

    #[Validate('required|numeric|min:30|max:60')]
    public $birth_length = '';

    #[Validate('nullable|numeric|min:20|max:45')]
    public $head_circumference = '';

    // Data Bayi - Kondisi Lahir
    #[Validate('nullable|integer|min:0|max:10')]
    public $apgar_score_1 = '';

    #[Validate('nullable|integer|min:0|max:10')]
    public $apgar_score_5 = '';

    #[Validate('required|in:Hidup,Meninggal,Asfiksia')]
    public $condition = 'Hidup';

    #[Validate('nullable|string')]
    public $congenital_defect = '';

    // Manajemen Bayi Baru Lahir (Checklist)
    public $imd_initiated = false;
    public $vit_k_given = false;
    public $eye_ointment_given = false;
    public $hb0_given = false;

    // UI state
    public $showSuccess = false;

    public function mount(Pregnancy $pregnancy)
    {
        $this->pregnancy = $pregnancy;

        // Check if delivery record already exists
        if ($this->pregnancy->deliveryRecord) {
            $this->deliveryRecord = $this->pregnancy->deliveryRecord;
            $this->loadExistingData();
        } else {
            // Set defaults
            $this->delivery_time = now()->format('H:i');
            $this->gestational_age = $this->pregnancy->gestational_age;
            $this->placenta_delivery = 'Spontan';
            $this->perineum_rupture = 'Utuh';
            $this->condition = 'Hidup';
        }
    }

    private function loadExistingData()
    {
        $dr = $this->deliveryRecord;

        $this->delivery_date = $dr->delivery_date_time->format('Y-m-d');
        $this->delivery_time = $dr->delivery_date_time->format('H:i');
        $this->gestational_age = $dr->gestational_age;
        $this->birth_attendant = $dr->birth_attendant;
        $this->place_of_birth = $dr->place_of_birth;

        $this->duration_first_stage = $dr->duration_first_stage;
        $this->duration_second_stage = $dr->duration_second_stage;
        $this->delivery_method = $dr->delivery_method;

        $this->placenta_delivery = $dr->placenta_delivery;
        $this->oxytocin_injection = $dr->oxytocin_injection;
        $this->controlled_cord_traction = $dr->controlled_cord_traction;
        $this->uterine_massage = $dr->uterine_massage;
        $this->perineum_rupture = $dr->perineum_rupture;
        $this->bleeding_amount = $dr->bleeding_amount;
        $this->blood_pressure = $dr->blood_pressure;
        $this->postpartum_monitoring_2h = $dr->postpartum_monitoring_2h;
        $this->postpartum_monitoring_2h = $dr->postpartum_monitoring_2h;
        $this->complications = $dr->complications;
        $this->service_fee = $dr->service_fee;

        $this->baby_name = $dr->baby_name;
        $this->gender = $dr->gender;
        $this->birth_weight = $dr->birth_weight;
        $this->birth_length = $dr->birth_length;
        $this->head_circumference = $dr->head_circumference;

        $this->apgar_score_1 = $dr->apgar_score_1;
        $this->apgar_score_5 = $dr->apgar_score_5;
        $this->condition = $dr->condition;
        $this->congenital_defect = $dr->congenital_defect;

        $this->imd_initiated = $dr->imd_initiated;
        $this->vit_k_given = $dr->vit_k_given;
        $this->eye_ointment_given = $dr->eye_ointment_given;
        $this->hb0_given = $dr->hb0_given;
    }

    public function save()
    {
        $this->validate();

        // Combine date and time
        $deliveryDateTime = $this->delivery_date . ' ' . $this->delivery_time;

        $data = [
            'pregnancy_id' => $this->pregnancy->id,
            'delivery_date_time' => $deliveryDateTime,
            'gestational_age' => $this->gestational_age,
            'birth_attendant' => $this->birth_attendant,
            'place_of_birth' => $this->place_of_birth,

            'duration_first_stage' => $this->duration_first_stage,
            'duration_second_stage' => $this->duration_second_stage,
            'delivery_method' => $this->delivery_method,

            'placenta_delivery' => $this->placenta_delivery,
            'oxytocin_injection' => $this->oxytocin_injection,
            'controlled_cord_traction' => $this->controlled_cord_traction,
            'uterine_massage' => $this->uterine_massage,
            'perineum_rupture' => $this->perineum_rupture,
            'bleeding_amount' => $this->bleeding_amount,
            'blood_pressure' => $this->blood_pressure,
            'postpartum_monitoring_2h' => $this->postpartum_monitoring_2h,
            'postpartum_monitoring_2h' => $this->postpartum_monitoring_2h,
            'complications' => $this->complications,
            'service_fee' => $this->service_fee ?: null,

            'baby_name' => $this->baby_name,
            'gender' => $this->gender,
            'birth_weight' => $this->birth_weight,
            'birth_length' => $this->birth_length,
            'head_circumference' => $this->head_circumference,

            'apgar_score_1' => $this->apgar_score_1,
            'apgar_score_5' => $this->apgar_score_5,
            'condition' => $this->condition,
            'congenital_defect' => $this->congenital_defect,

            'imd_initiated' => $this->imd_initiated,
            'vit_k_given' => $this->vit_k_given,
            'eye_ointment_given' => $this->eye_ointment_given,
            'hb0_given' => $this->hb0_given,
        ];

        if ($this->deliveryRecord) {
            // Update existing record
            $this->deliveryRecord->update($data);
            session()->flash('message', 'Data persalinan berhasil diperbarui!');
        } else {
            // Create new record (Observer will auto-create child & HB0)
            DeliveryRecord::create($data);
            session()->flash('message', 'Data persalinan berhasil disimpan! Child record dan imunisasi HB0 otomatis dibuat.');
        }

        $this->showSuccess = true;
        $this->dispatch('delivery-saved');

        return redirect()->route('patients.show', $this->pregnancy->patient_id);
    }

    public function render()
    {
        return view('livewire.delivery-entry');
    }
}
