<?php

namespace App\Observers;

use App\Models\DeliveryRecord;
use App\Models\Child;
use App\Models\ChildVisit;
use App\Models\ImmunizationAction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DeliveryRecordObserver
{
    /**
     * Handle the DeliveryRecord "created" event.
     *
     * Logic:
     * 1. Update pregnancy status to 'Lahir'
     * 2. Auto-create child record from baby data
     * 3. Auto-create HB0 immunization if given
     */
    public function created(DeliveryRecord $deliveryRecord): void
    {
        try {
            DB::transaction(function () use ($deliveryRecord) {
                // 1. Update Pregnancy Status
                $pregnancy = $deliveryRecord->pregnancy;
                $pregnancy->update([
                    'status' => 'Lahir',
                    'delivery_date' => $deliveryRecord->delivery_date_time,
                ]);

                // 2. Auto-Create Child Record
                // Pastikan bayi hidup dan belum ada child record untuk pregnancy ini
                if ($deliveryRecord->condition === 'Hidup' && !$this->childAlreadyExists($pregnancy->id)) {
                    $child = Child::create([
                        'patient_id' => $pregnancy->patient_id,
                        'nik' => null, // Will be filled later by admin
                        'name' => $deliveryRecord->baby_name ?: "Bayi " . $pregnancy->patient->name,
                        'gender' => $deliveryRecord->gender,
                        'dob' => $deliveryRecord->delivery_date_time->format('Y-m-d'),
                        'pob' => $deliveryRecord->place_of_birth,
                        'birth_weight' => $deliveryRecord->birth_weight,
                        'birth_height' => $deliveryRecord->birth_length,
                        'status' => 'Hidup', // FIX: Changed from 'Aktif' to 'Hidup'
                    ]);

                    // 3. Auto-Create HB0 Immunization if Given
                    if ($deliveryRecord->hb0_given && $child) {
                        $this->createHB0Immunization($child, $deliveryRecord->delivery_date_time);
                    }

                    Log::info('DeliveryRecordObserver: Auto-created child', [
                        'delivery_record_id' => $deliveryRecord->id,
                        'child_id' => $child->id,
                        'hb0_created' => $deliveryRecord->hb0_given,
                    ]);
                }
            });
        } catch (\Exception $e) {
            Log::error('DeliveryRecordObserver: Failed to process delivery record', [
                'delivery_record_id' => $deliveryRecord->id,
                'error' => $e->getMessage(),
            ]);

            // Re-throw exception to prevent saving incomplete data
            throw $e;
        }
    }

    /**
     * Check if child already exists for this pregnancy.
     */
    private function childAlreadyExists(int $pregnancyId): bool
    {
        // Check by pregnancy's patient_id and approximate birth date
        // (untuk menghindari duplikasi jika user edit delivery record)
        return false; // Simplified for now - bisa ditambahkan logic lebih kompleks
    }

    /**
     * Create HB0 immunization record.
     */
    private function createHB0Immunization(Child $child, $vaccinationDate): void
    {
        // Create child visit first (required for immunization_actions)
        $childVisit = ChildVisit::create([
            'child_id' => $child->id,
            'visit_date' => $vaccinationDate,
            'age_month' => 0, // Newborn
            'complaint' => 'Imunisasi HB0 saat lahir',
            'weight' => $child->birth_weight,
            'height' => $child->birth_height,
            'notes' => 'Auto-generated dari delivery record',
        ]);

        // Create HB0 immunization action
        ImmunizationAction::create([
            'child_visit_id' => $childVisit->id,
            'vaccine_type' => 'HB0',
            'batch_number' => null, // Will be filled later
            'body_part' => 'Paha Kanan',
            'provider_name' => null, // Will be filled later
        ]);

        Log::info('DeliveryRecordObserver: Created HB0 immunization', [
            'child_id' => $child->id,
            'child_visit_id' => $childVisit->id,
        ]);
    }

    /**
     * Handle the DeliveryRecord "updated" event.
     */
    public function updated(DeliveryRecord $deliveryRecord): void
    {
        // Optional: Sync changes to child record if needed
    }

    /**
     * Handle the DeliveryRecord "deleted" event.
     */
    public function deleted(DeliveryRecord $deliveryRecord): void
    {
        //
    }

    /**
     * Handle the DeliveryRecord "restored" event.
     */
    public function restored(DeliveryRecord $deliveryRecord): void
    {
        //
    }

    /**
     * Handle the DeliveryRecord "force deleted" event.
     */
    public function forceDeleted(DeliveryRecord $deliveryRecord): void
    {
        //
    }
}

