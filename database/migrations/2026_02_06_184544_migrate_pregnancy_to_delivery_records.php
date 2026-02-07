<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use App\Models\Pregnancy;
use App\Models\DeliveryRecord;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * IMPORTANT: This migration is OPTIONAL and should only be run IF:
     * 1. You have existing pregnancy data with delivery info (status = 'Lahir')
     * 2. You want to migrate those to the new DeliveryRecord table
     *
     * WARNING: Run on backup database first!
     */
    public function up(): void
    {
        // Get all pregnancies with status 'Lahir' that DON'T have delivery records
        $pregnancies = Pregnancy::where('status', 'Lahir')
            ->doesntHave('deliveryRecord')
            ->whereNotNull('delivery_date')
            ->get();

        $migrated = 0;
        $skipped = 0;

        foreach ($pregnancies as $pregnancy) {
            // Skip if no delivery date
            if (!$pregnancy->delivery_date) {
                $skipped++;
                continue;
            }

            // Create delivery record from pregnancy data
            try {
                // Map delivery method to match delivery_records ENUM
                $deliveryMethod = match($pregnancy->delivery_method) {
                    'Normal' => 'Spontan Belakang Kepala',
                    'Caesar/Sectio' => 'Sectio Caesarea',
                    'Vakum' => 'Vakum',
                    default => 'Spontan Belakang Kepala',
                };

                DeliveryRecord::create([
                    'pregnancy_id' => $pregnancy->id,
                    'delivery_date_time' => $pregnancy->delivery_date,
                    'gestational_age' => $pregnancy->gestational_age ?? 38, // Default if null
                    'birth_attendant' => $pregnancy->birth_attendant ?? 'Bidan',
                    'place_of_birth' => $pregnancy->place_of_birth ?? 'Klinik',

                    // Cara Lahir (use mapped value)
                    'delivery_method' => $deliveryMethod,

                    // Placenta
                    'placenta_delivery' => 'Spontan', // Default
                    'perineum_rupture' => 'Utuh', // Default

                    // Baby Data
                    'baby_name' => $pregnancy->baby_name ?? null,
                    'gender' => $pregnancy->baby_gender ?? 'L',
                    'birth_weight' => $pregnancy->birth_weight ?? 3000,
                    'birth_length' => $pregnancy->birth_length ?? 48,

                    'condition' => $pregnancy->outcome ?? 'Hidup',
                    'complications' => $pregnancy->complications ?? null,

                    // Defaults for new fields
                    'imd_initiated' => false,
                    'vit_k_given' => false,
                    'eye_ointment_given' => false,
                    'hb0_given' => false,
                ]);

                $migrated++;
            } catch (\Exception $e) {
                // Log error but continue
                \Log::error("Failed to migrate pregnancy ID {$pregnancy->id}: " . $e->getMessage());
                $skipped++;
            }
        }

        // Output summary
        \Log::info("Data Migration Summary:");
        \Log::info("- Migrated: {$migrated} pregnancy records to delivery_records");
        \Log::info("- Skipped: {$skipped} records");
    }

    /**
     * Reverse the migrations.
     *
     * WARNING: This will DELETE all delivery records created by this migration!
     */
    public function down(): void
    {
        // Only delete delivery records created during this migration
        // (those without associated child records created by Observer)

        // Option 1: Delete ALL delivery records (dangerous!)
        // DeliveryRecord::truncate();

        // Option 2: Don't delete anything (safer)
        \Log::warning("down() migration not implemented for safety. Manual cleanup required.");
    }
};
