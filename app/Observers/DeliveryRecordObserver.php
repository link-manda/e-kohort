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
                // 1. Sync Delivery Summary to Pregnancy
                $pregnancy = $deliveryRecord->pregnancy;
                $pregnancy->update([
                    'status' => 'Lahir',
                    'delivery_date' => $deliveryRecord->delivery_date_time,
                    'delivery_method' => $this->mapDeliveryMethodForPregnancy($deliveryRecord->delivery_method),
                    'birth_attendant' => $deliveryRecord->birth_attendant,
                    'place_of_birth' => $deliveryRecord->place_of_birth,
                    'outcome' => $deliveryRecord->condition,
                    'baby_gender' => $deliveryRecord->gender,
                    'complications' => $deliveryRecord->complications,
                ]);

                // 2. Auto-Create Child Record
                // Only if baby is alive and no duplicate exists
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
                        'birth_location' => 'internal', // Delivery at this clinic
                        'status' => 'Hidup',
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
     * Check if child already exists for this delivery.
     * DUPLICATE PREVENTION: Check by patient_id + DOB to handle:
     * - Re-editing delivery records
     * - External births registered separately
     */
    private function childAlreadyExists(int $pregnancyId): bool
    {
        $delivery = DeliveryRecord::where('pregnancy_id', $pregnancyId)->first();

        if (!$delivery) {
            return false;
        }

        $pregnancy = $delivery->pregnancy;
        $dob = $delivery->delivery_date_time->toDateString();

        // Check if child with same DOB and mother already exists
        $existingChild = Child::where('patient_id', $pregnancy->patient_id)
            ->where('dob', $dob)
            ->exists();

        if ($existingChild) {
            Log::info('Child already exists - skipping creation', [
                'patient_id' => $pregnancy->patient_id,
                'dob' => $dob,
            ]);
        }

        return $existingChild;
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
        // Note: immunization_actions uses vaccine_type string, not vaccine_id FK
        ImmunizationAction::create([
            'child_visit_id' => $childVisit->id,
            'vaccine_type' => 'HB0',
            'batch_number' => 'AUTO-' . now()->format('Ymd'),
            'body_part' => 'Paha Kanan',
            'provider_name' => 'Bidan (Auto-generated)',
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

    /**
     * Map detailed delivery methods from delivery_records to simplified pregnancy enum.
     *
     * delivery_records ENUM: 'Spontan Belakang Kepala', 'Sungsang', 'Vakum', 'Sectio Caesarea'
     * pregnancies ENUM: 'Normal', 'Caesar/Sectio', 'Vakum'
     */
    private function mapDeliveryMethodForPregnancy(string $deliveryMethod): string
    {
        return match ($deliveryMethod) {
            'Spontan Belakang Kepala', 'Sungsang' => 'Normal',
            'Sectio Caesarea' => 'Caesar/Sectio',
            'Vakum' => 'Vakum',
            default => 'Normal', // Fallback to Normal
        };
    }
}

