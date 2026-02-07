<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Patient;
use App\Models\Pregnancy;
use Illuminate\Support\Facades\DB;

class UpdatePregnancyStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Update Ni Komang Sriani's pregnancy to 'Lahir' for testing
     */
    public function run(): void
    {
        // Find patient
        $patient = Patient::where('name', 'LIKE', '%Ni Komang Sriani%')->first();

        if (!$patient) {
            $this->command->error("Patient 'Ni Komang Sriani' not found!");
            return;
        }

        $this->command->info("Found patient: {$patient->name} (ID: {$patient->id})");

        // Get or create pregnancy
        $pregnancy = $patient->pregnancies()->first();

        if (!$pregnancy) {
            $this->command->info("No pregnancies found. Creating sample pregnancy...");

            $pregnancy = Pregnancy::create([
                'patient_id' => $patient->id,
                'hpht' => now()->subMonths(9),
                'hpl' => now()->addDays(7),
                'gravida' => 1,
                'para' => 0,
                'abortus' => 0,
                'status' => 'Aktif',
            ]);

            $this->command->info("Created pregnancy ID: {$pregnancy->id}");
        }

        // Update to 'Lahir' status
        $oldStatus = $pregnancy->status;
        $pregnancy->update([
            'status' => 'Lahir',
            'delivery_date' => now(),
            'delivery_method' => 'Spontan Belakang Kepala',
            'birth_attendant' => 'Bidan Yanti',
            'place_of_birth' => 'Klinik E-Kohort',
            'outcome' => 'Hidup',
            'baby_gender' => 'P',
        ]);

        $this->command->info("✅ Updated pregnancy status: {$oldStatus} -> Lahir");
        $this->command->info("Pregnancy ID: {$pregnancy->id}");
        $this->command->line("");
        $this->command->info("Now you can test the delivery tab!");
        $this->command->info("Visit: http://127.0.0.1:8000/patients/{$patient->id}");
    }
}
