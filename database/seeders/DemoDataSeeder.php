<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Patient;
use App\Models\Pregnancy;
use App\Models\AncVisit;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create demo user (Bidan)
        $user = User::create([
            'name' => 'Bidan Demo',
            'email' => 'bidan@demo.com',
            'password' => Hash::make('password'),
        ]);

        // Create demo patients with pregnancies and visits
        $patients = [
            [
                'name' => 'Ni Ketut Sari',
                'nik' => '5103014501950001',
                'dob' => '1995-01-05',
                'blood_type' => 'O',
                'husband_name' => 'I Made Budi',
            ],
            [
                'name' => 'Ni Wayan Dewi',
                'nik' => '5103015202980002',
                'dob' => '1998-02-12',
                'blood_type' => 'A',
                'husband_name' => 'I Ketut Agus',
            ],
            [
                'name' => 'Ni Made Ayu',
                'nik' => '5103016303000003',
                'dob' => '2000-03-23',
                'blood_type' => 'B',
                'husband_name' => 'I Wayan Susila',
            ],
        ];

        foreach ($patients as $patientData) {
            $patient = Patient::create([
                'nik' => $patientData['nik'],
                'name' => $patientData['name'],
                'dob' => $patientData['dob'],
                'address' => 'Jl. Raya Denpasar No. ' . rand(1, 100) . ', Bali',
                'phone' => '08' . rand(100000000, 999999999),
                'blood_type' => $patientData['blood_type'],
                'husband_name' => $patientData['husband_name'],
                'husband_nik' => '510301' . rand(1000000000, 9999999999),
                'husband_job' => ['Petani', 'Wiraswasta', 'PNS'][rand(0, 2)],
            ]);

            // Create active pregnancy
            $hpht = now()->subMonths(rand(4, 7));
            $pregnancy = Pregnancy::create([
                'patient_id' => $patient->id,
                'gravida' => 'G' . rand(1, 3) . 'P' . rand(0, 2) . 'A' . rand(0, 1),
                'hpht' => $hpht,
                'hpl' => $hpht->copy()->addMonths(9),
                'status' => 'Aktif',
            ]);

            // Create ANC visits
            $visitDates = [
                now()->subDays(rand(1, 7)),
                now()->subDays(rand(10, 20)),
                now()->subDays(rand(25, 35)),
            ];

            foreach ($visitDates as $index => $visitDate) {
                $systolic = rand(100, 150);
                $diastolic = rand(60, 95);
                $map = round($diastolic + (($systolic - $diastolic) / 3), 2);

                $riskCategory = 'Rendah';
                if ($systolic >= 140 || $map > 100) {
                    $riskCategory = rand(0, 1) ? 'Tinggi' : 'Ekstrem';
                }

                AncVisit::create([
                    'pregnancy_id' => $pregnancy->id,
                    'visit_date' => $visitDate,
                    'trimester' => min(3, floor($hpht->diffInWeeks($visitDate) / 13) + 1),
                    'visit_code' => 'K' . ($index + 1),
                    'gestational_age' => $hpht->diffInWeeks($visitDate),
                    'weight' => rand(450, 750) / 10,
                    'height' => rand(150, 170),
                    'lila' => rand(200, 300) / 10,
                    'systolic' => $systolic,
                    'diastolic' => $diastolic,
                    'map_score' => $map,
                    'tfu' => min(40, $hpht->diffInWeeks($visitDate)),
                    'djj' => rand(120, 160),
                    'hb' => rand(90, 130) / 10,
                    'protein_urine' => ['Negatif', 'Negatif', 'Negatif', '+1'][rand(0, 3)],
                    'hiv_status' => 'NR',
                    'syphilis_status' => 'NR',
                    'hbsag_status' => 'NR',
                    'tt_immunization' => ['T1', 'T2', 'T3'][rand(0, 2)],
                    'fe_tablets' => rand(30, 90),
                    'risk_category' => $riskCategory,
                    'diagnosis' => 'Pemeriksaan rutin ANC',
                ]);
            }
        }

        $this->command->info('Demo data created successfully!');
        $this->command->info('Login credentials:');
        $this->command->info('Email: bidan@demo.com');
        $this->command->info('Password: password');
    }
}
