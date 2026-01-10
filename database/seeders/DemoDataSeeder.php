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
        // Create demo user (Bidan) and assign Admin role
        $user = User::create([
            'name' => 'Bidan Demo',
            'email' => 'bidan@demo.com',
            'password' => Hash::make('password'),
        ]);

        // Assign Admin role to demo user
        $user->assignRole('Admin');

        // Data configuration untuk generate 50+ pasien
        $firstNames = ['Ketut', 'Wayan', 'Made', 'Nyoman', 'Kadek', 'Putu', 'Komang', 'Gede'];
        $lastNames = ['Sari', 'Dewi', 'Ayu', 'Sri', 'Wati', 'Ningsih', 'Puspita', 'Lestari', 'Widyani', 'Suartini'];
        $maleFirstNames = ['Made', 'Wayan', 'Ketut', 'Nyoman', 'Komang', 'Putu', 'Gede'];
        $maleLastNames = ['Budi', 'Agus', 'Susila', 'Arta', 'Wirawan', 'Putra', 'Jaya', 'Ngurah', 'Sudana', 'Artha'];
        $bloodTypes = ['A', 'B', 'AB', 'O'];
        $educations = ['SD', 'SMP', 'SMA', 'D3', 'S1'];
        $jobs = ['Ibu Rumah Tangga', 'Karyawan Swasta', 'Wiraswasta', 'PNS', 'Guru', 'Pedagang'];
        $husbandJobs = ['Petani', 'Wiraswasta', 'PNS', 'Karyawan Swasta', 'Pedagang', 'Driver', 'Guru', 'Buruh'];
        $cities = ['Denpasar', 'Gianyar', 'Badung', 'Tabanan', 'Klungkung', 'Bangli', 'Karangasem', 'Buleleng'];
        $streets = ['Jl. Raya Denpasar', 'Jl. Sunset Road', 'Jl. Gatot Subroto', 'Jl. Diponegoro', 'Jl. Hayam Wuruk', 'Jl. Teuku Umar', 'Jl. Gajah Mada', 'Br. Kuta', 'Br. Legian', 'Br. Seminyak'];

        // Generate 55 pasien
        for ($i = 1; $i <= 55; $i++) {
            $firstName = $firstNames[array_rand($firstNames)];
            $lastName = $lastNames[array_rand($lastNames)];
            $patientName = "Ni {$firstName} {$lastName} " . ($i > 10 ? $i : '');

            $maleFirst = $maleFirstNames[array_rand($maleFirstNames)];
            $maleLast = $maleLastNames[array_rand($maleLastNames)];
            $husbandName = "I {$maleFirst} {$maleLast}";

            // Generate realistic NIK (16 digit)
            $nikPrefix = '5103' . str_pad(rand(1, 31), 2, '0', STR_PAD_LEFT);
            $nikMiddle = str_pad(rand(1, 12), 2, '0', STR_PAD_LEFT);
            $nikYear = str_pad(rand(85, 99), 2, '0', STR_PAD_LEFT);
            $nikSuffix = str_pad($i, 4, '0', STR_PAD_LEFT);
            $nik = $nikPrefix . $nikMiddle . $nikYear . $nikSuffix;

            // Generate DOB (age 20-35 years old)
            $age = rand(20, 35);
            $dob = now()->subYears($age)->subMonths(rand(0, 11))->format('Y-m-d');

            $patient = Patient::create([
                'no_rm' => 'RM-' . str_pad($i, 6, '0', STR_PAD_LEFT),
                'nik' => $nik,
                'no_kk' => '510301' . rand(10000000, 99999999),
                'no_bpjs' => '000' . rand(1000000000, 9999999999),
                'name' => $patientName,
                'dob' => $dob,
                'pob' => $cities[array_rand($cities)],
                'address' => $streets[array_rand($streets)] . ' No. ' . rand(1, 150) . ', Bali',
                'phone' => '08' . rand(1, 9) . rand(100000000, 999999999),
                'blood_type' => $bloodTypes[array_rand($bloodTypes)],
                'job' => $jobs[array_rand($jobs)],
                'education' => $educations[array_rand($educations)],
                'husband_name' => $husbandName,
                'husband_nik' => '5103' . rand(100000000000, 999999999999),
                'husband_job' => $husbandJobs[array_rand($husbandJobs)],
                'husband_education' => $educations[array_rand($educations)],
                'husband_blood_type' => $bloodTypes[array_rand($bloodTypes)],
            ]);

            // Create active pregnancy with varied data
            $weeksPregnant = rand(8, 32); // 2-8 months pregnant
            $hpht = now()->subWeeks($weeksPregnant);

            $gravida_g = rand(1, 5);
            $gravida_p = $gravida_g > 1 ? rand(0, $gravida_g - 1) : 0;
            $gravida_a = $gravida_g > 1 ? rand(0, min(2, $gravida_g - $gravida_p - 1)) : 0;

            $pregnancy = Pregnancy::create([
                'patient_id' => $patient->id,
                'gravida' => "G{$gravida_g}P{$gravida_p}A{$gravida_a}",
                'hpht' => $hpht,
                'hpl' => $hpht->copy()->addMonths(9),
                'pregnancy_gap' => $gravida_g > 1 ? rand(1, 10) : null,
                'weight_before' => rand(450, 700) / 10,
                'height' => rand(145, 170),
                'risk_score_initial' => rand(2, 8),
                'status' => 'Aktif',
            ]);

            // Create ANC visits based on gestational age
            $currentWeeks = $hpht->diffInWeeks(now());
            $visitCount = min(6, max(1, floor($currentWeeks / 4))); // 1 visit per month

            for ($v = 0; $v < $visitCount; $v++) {
                $weeksAgo = ($visitCount - $v) * 4; // Space visits 4 weeks apart
                $visitDate = now()->subWeeks($weeksAgo)->addDays(rand(-3, 3));
                $gestationalAge = $hpht->diffInWeeks($visitDate);

                // Varied vital signs - some with risk factors
                $hasRisk = rand(1, 100) <= 20; // 20% chance of risk factors
                $systolic = $hasRisk ? rand(130, 160) : rand(100, 130);
                $diastolic = $hasRisk ? rand(80, 100) : rand(60, 85);
                $map = round($diastolic + (($systolic - $diastolic) / 3), 2);

                $lila = $hasRisk && rand(1, 100) <= 30 ? rand(180, 230) / 10 : rand(240, 320) / 10;
                $hb = $hasRisk && rand(1, 100) <= 40 ? rand(80, 105) / 10 : rand(105, 140) / 10;

                $riskCategory = 'Rendah';
                if ($systolic >= 140 || $map > 100 || $lila < 23.5 || $hb < 11) {
                    $riskCategory = $map > 110 || $hb < 9 ? 'Ekstrem' : 'Tinggi';
                }

                $trimester = min(3, floor($gestationalAge / 13) + 1);
                $weightGain = ($gestationalAge / 40) * rand(80, 150) / 10; // Progressive weight gain

                AncVisit::create([
                    'pregnancy_id' => $pregnancy->id,
                    'visit_date' => $visitDate,
                    'trimester' => $trimester,
                    'visit_code' => 'K' . ($v + 1),
                    'gestational_age' => $gestationalAge,
                    'weight' => ($pregnancy->weight_before ?? 55) + $weightGain,
                    'lila' => $lila,
                    'systolic' => $systolic,
                    'diastolic' => $diastolic,
                    'map_score' => $map,
                    'tfu' => max(0, $gestationalAge - 12), // TFU ≈ gestational age after 12 weeks
                    'djj' => $gestationalAge >= 12 ? rand(120, 160) : null,
                    'hb' => $hb,
                    'protein_urine' => $hasRisk ? ['+1', '+2', 'Negatif'][rand(0, 2)] : 'Negatif',
                    'hiv_status' => rand(1, 100) <= 98 ? 'NR' : 'Unchecked',
                    'syphilis_status' => rand(1, 100) <= 98 ? 'NR' : 'Unchecked',
                    'hbsag_status' => rand(1, 100) <= 98 ? 'NR' : 'Unchecked',
                    'tt_immunization' => ['T1', 'T2', 'T3', 'T4', 'T5'][min($v, 4)],
                    'fe_tablets' => rand(30, 90),
                    'risk_category' => $riskCategory,
                    'diagnosis' => $riskCategory === 'Rendah' ? 'Pemeriksaan rutin ANC' : 'Kehamilan risiko ' . strtolower($riskCategory),
                ]);
            }
        }

        $this->command->info('✅ 55 patients with realistic pregnancy data created!');
        $this->command->info('📊 Varied risk levels, multiple visits per patient');
        $this->command->info('');
        $this->command->info('Login credentials:');
        $this->command->info('Email: bidan@demo.com');
        $this->command->info('Password: password');
    }
}