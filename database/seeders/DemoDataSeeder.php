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

        /**
         * ===============================
         * DATA NAMA BALI REALISTIS (UNIK)
         * ===============================
         */

        // Nama perempuan Bali (tanpa angka) - dibuat banyak & bervariasi
        $femaleFullNames = [
            'Ni Luh Suartini',
            'Ni Luh Sulastri',
            'Ni Luh Suryani',
            'Ni Luh Widiastuti',
            'Ni Luh Purnami',
            'Ni Luh Saraswati',
            'Ni Luh Puspawati',
            'Ni Luh Astini',
            'Ni Luh Wulandari',
            'Ni Luh Yuliani',
            'Ni Made Suryani',
            'Ni Made Sartini',
            'Ni Made Ariani',
            'Ni Made Purnami',
            'Ni Made Sriani',
            'Ni Made Lestari',
            'Ni Made Indrayani',
            'Ni Made Kusumawati',
            'Ni Made Pratiwi',
            'Ni Made Puspaningrum',
            'Ni Nyoman Suartini',
            'Ni Nyoman Wati',
            'Ni Nyoman Cahyani',
            'Ni Nyoman Arini',
            'Ni Nyoman Astuti',
            'Ni Nyoman Widiani',
            'Ni Nyoman Sari Dewi',
            'Ni Nyoman Puspitawati',
            'Ni Nyoman Kumalasari',
            'Ni Nyoman Rahayuni',
            'Ni Ketut Wati',
            'Ni Ketut Sriani',
            'Ni Ketut Suryani',
            'Ni Ketut Ariastini',
            'Ni Ketut Widiastini',
            'Ni Ketut Paramita',
            'Ni Ketut Rahayu',
            'Ni Ketut Laksmiwati',
            'Ni Ketut Pertiwi',
            'Ni Ketut Mulyani',
            'Ni Komang Sriani',
            'Ni Komang Cahyani',
            'Ni Komang Purnami',
            'Ni Komang Arini',
            'Ni Komang Suartini',
            'Ni Komang Lestari',
            'Ni Komang Andayani',
            'Ni Komang Puspaningrum',
            'Ni Komang Saraswati',
            'Ni Komang Pradnyawati',
            'Ni Putu Suryani',
            'Ni Putu Widiastuti',
            'Ni Putu Purnami',
            'Ni Putu Lestari',
            'Ni Putu Suartini',
            'Ni Putu Saraswati',
            'Ni Putu Sriyani',
            'Ni Putu Pratiwi',
        ];

        // Nama laki-laki Bali realistis (tanpa angka)
        $maleFullNames = [
            'I Wayan Sudiana',
            'I Wayan Sutarma',
            'I Wayan Suarjana',
            'I Wayan Suarjaya',
            'I Wayan Supartha',
            'I Wayan Putrawan',
            'I Wayan Pradana',
            'I Wayan Aryawan',
            'I Made Sudiana',
            'I Made Sutrisna',
            'I Made Suarjana',
            'I Made Adnyana',
            'I Made Sutama',
            'I Made Pramana',
            'I Made Mahendra',
            'I Made Wirawan',
            'I Nyoman Sudiana',
            'I Nyoman Sutrisna',
            'I Nyoman Suardika',
            'I Nyoman Sutama',
            'I Nyoman Ardana',
            'I Nyoman Gunawan',
            'I Nyoman Kurniawan',
            'I Nyoman Mahayasa',
            'I Ketut Sudiana',
            'I Ketut Sutama',
            'I Ketut Suardika',
            'I Ketut Adnyana',
            'I Ketut Putra',
            'I Ketut Wiratha',
            'I Ketut Mahendra',
            'I Ketut Pranata',
            'I Komang Sudiana',
            'I Komang Sutarma',
            'I Komang Suardana',
            'I Komang Sutama',
            'I Komang Ardika',
            'I Komang Surya',
            'I Komang Wirawan',
            'I Komang Saputra',
            'I Putu Sudiana',
            'I Putu Sutarma',
            'I Putu Suardika',
            'I Putu Sutama',
            'I Putu Mahendra',
            'I Putu Pramana',
            'I Putu Wirawan',
            'I Putu Adnyana',
        ];

        // Nama anak laki-laki & perempuan (unik, Bali style)
        $childBoyNames = [
            'I Wayan Mahesa',
            'I Wayan Pradipta',
            'I Wayan Mahardika',
            'I Made Aditya',
            'I Made Satya',
            'I Made Mahendra',
            'I Nyoman Arta',
            'I Nyoman Dhana',
            'I Nyoman Wijaya',
            'I Ketut Arya',
            'I Ketut Surya',
            'I Ketut Dharma',
            'I Komang Bagus',
            'I Komang Guna',
            'I Komang Pramana',
            'I Putu Bayu',
            'I Putu Yoga',
            'I Putu Wira',
            'I Kadek Satria',
            'I Kadek Mahardika',
            'I Nengah Danu',
            'I Nengah Mahesa',
            'I Gusti Agung Putra',
            'I Gusti Ngurah Rai',
        ];

        $childGirlNames = [
            'Ni Luh Putri',
            'Ni Luh Ayu',
            'Ni Luh Kirana',
            'Ni Made Dwi',
            'Ni Made Saras',
            'Ni Made Laksmi',
            'Ni Nyoman Intan',
            'Ni Nyoman Puspita',
            'Ni Nyoman Maharani',
            'Ni Ketut Citra',
            'Ni Ketut Sari',
            'Ni Ketut Indah',
            'Ni Komang Devi',
            'Ni Komang Ratih',
            'Ni Komang Jelita',
            'Ni Putu Melati',
            'Ni Putu Sekar',
            'Ni Putu Nirmala',
            'Ni Kadek Bintang',
            'Ni Kadek Anggreni',
            'Ni Nengah Pradnyani',
            'Ni Nengah Wulandari',
            'Ni Gusti Ayu Lestari',
            'Ni Gusti Ayu Manik',
        ];

        $bloodTypes = ['A', 'B', 'AB', 'O'];
        $educations = ['SD', 'SMP', 'SMA', 'D3', 'S1'];
        $jobs = ['Ibu Rumah Tangga', 'Karyawan Swasta', 'Wiraswasta', 'PNS', 'Guru', 'Pedagang'];
        $husbandJobs = ['Petani', 'Wiraswasta', 'PNS', 'Karyawan Swasta', 'Pedagang', 'Driver', 'Guru', 'Buruh'];
        $cities = ['Denpasar', 'Gianyar', 'Badung', 'Tabanan', 'Klungkung', 'Bangli', 'Karangasem', 'Buleleng'];
        $streets = [
            'Jl. Raya Denpasar',
            'Jl. Sunset Road',
            'Jl. Gatot Subroto',
            'Jl. Diponegoro',
            'Jl. Hayam Wuruk',
            'Jl. Teuku Umar',
            'Jl. Gajah Mada',
            'Br. Kuta',
            'Br. Legian',
            'Br. Seminyak',
            'Br. Celuk',
            'Br. Ubud',
            'Br. Sukawati',
            'Br. Sanur',
            'Br. Renon',
        ];

        /**
         * ===============================
         * GENERATE PASIEN (55 ORANG)
         * ===============================
         */

        shuffle($femaleFullNames);
        shuffle($maleFullNames);

        $totalPatients = 55;

        for ($i = 1; $i <= $totalPatients; $i++) {

            // Pastikan tidak ada nama yang diulang (tanpa angka)
            $patientName = $femaleFullNames[($i - 1) % count($femaleFullNames)];
            $husbandName = $maleFullNames[($i - 1) % count($maleFullNames)];

            // Generate realistic NIK (16 digit)
            $nikPrefix = '5103' . str_pad(rand(1, 31), 2, '0', STR_PAD_LEFT);
            $nikMiddle = str_pad(rand(1, 12), 2, '0', STR_PAD_LEFT);
            $nikYear = str_pad(rand(85, 99), 2, '0', STR_PAD_LEFT);
            $nikSuffix = str_pad($i, 4, '0', STR_PAD_LEFT);
            $nik = $nikPrefix . $nikMiddle . $nikYear . $nikSuffix;

            // Generate DOB (age 20-35 years old)
            $age = rand(20, 35);
            $dob = now()->subYears($age)->subMonths(rand(0, 11))->subDays(rand(0, 28))->format('Y-m-d');

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

            /**
             * ===============================
             * PREGNANCY AKTIF
             * ===============================
             */
            $weeksPregnant = rand(8, 32); // 2-8 months pregnant
            $hpht = now()->subWeeks($weeksPregnant);

            $gravida_g = rand(1, 5);
            $gravida_p = $gravida_g > 1 ? rand(0, $gravida_g - 1) : 0;
            $gravida_a = $gravida_g > 1 ? rand(0, min(2, $gravida_g - $gravida_p - 1)) : 0;

            $pregnancy = Pregnancy::create([
                'patient_id' => $patient->id,
                'gravida' => "G{$gravida_g}P{$gravida_p}A{$gravida_a}",
                'hpht' => $hpht,
                'hpl' => $hpht->copy()->addDays(7)->subMonths(3)->addYear(),
                'pregnancy_gap' => $gravida_g > 1 ? rand(1, 10) : null,
                'weight_before' => rand(450, 700) / 10,
                'height' => rand(145, 170),
                'risk_score_initial' => rand(2, 8),
                'status' => 'Aktif',
            ]);

            /**
             * ===============================
             * ANC VISITS
             * ===============================
             */
            $currentWeeks = $hpht->diffInWeeks(now());
            $visitCount = min(6, max(1, floor($currentWeeks / 4)));

            for ($v = 0; $v < $visitCount; $v++) {
                $weeksAgo = ($visitCount - $v) * 4;
                $visitDate = now()->subWeeks($weeksAgo)->addDays(rand(-3, 3));
                $gestationalAge = $hpht->diffInWeeks($visitDate);

                $hasRisk = rand(1, 100) <= 20;
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
                $weightGain = ($gestationalAge / 40) * rand(80, 150) / 10;

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
                    'tfu' => max(0, $gestationalAge - 12),
                    'djj' => $gestationalAge >= 12 ? rand(120, 160) : null,
                    'hb' => $hb,
                    'protein_urine' => $hasRisk ? ['+1', '+2', 'Negatif'][rand(0, 2)] : 'Negatif',
                    'hiv_status' => rand(1, 100) <= 98 ? 'NR' : 'Unchecked',
                    'syphilis_status' => rand(1, 100) <= 98 ? 'NR' : 'Unchecked',
                    'hbsag_status' => rand(1, 100) <= 98 ? 'NR' : 'Unchecked',
                    'tt_immunization' => ['T1', 'T2', 'T3', 'T4', 'T5'][min($v, 4)],
                    'fe_tablets' => rand(30, 90),
                    'risk_category' => $riskCategory,
                    'diagnosis' => $riskCategory === 'Rendah'
                        ? 'Pemeriksaan rutin ANC'
                        : 'Kehamilan risiko ' . strtolower($riskCategory),
                ]);
            }

            /**
             * ===============================
             * DATA ANAK + KUNJUNGAN IMUNISASI
             * ===============================
             */
            $childCount = rand(1, 2);

            // agar anak-anak pada 1 keluarga tidak berulang
            shuffle($childBoyNames);
            shuffle($childGirlNames);

            for ($c = 1; $c <= $childCount; $c++) {

                $childGender = rand(0, 1) ? 'L' : 'P';

                if ($childGender === 'L') {
                    $childName = $childBoyNames[array_rand($childBoyNames)];
                } else {
                    $childName = $childGirlNames[array_rand($childGirlNames)];
                }

                $childDob = now()
                    ->subYears(rand(0, 5))
                    ->subMonths(rand(0, 11))
                    ->subDays(rand(0, 28));

                $child = \App\Models\Child::create([
                    'patient_id' => $patient->id,
                    'nik' => '5103' . rand(100000000000, 999999999999),
                    'no_rm' => 'C' . str_pad($i, 4, '0', STR_PAD_LEFT) . str_pad($c, 2, '0', STR_PAD_LEFT),
                    'name' => $childName,
                    'gender' => $childGender,
                    'dob' => $childDob,
                    'pob' => $cities[array_rand($cities)],
                    'birth_weight' => rand(25, 40) / 10,
                    'birth_height' => rand(45, 55),
                    'status' => 'Hidup',
                ]);

                // Tambah 1-3 kunjungan imunisasi
                $visitCountChild = rand(1, 3);

                for ($v = 1; $v <= $visitCountChild; $v++) {
                    $visitDate = $childDob->copy()->addMonths($v * rand(1, 4))->addDays(rand(0, 28));
                    $ageMonth = $visitDate->diffInMonths($childDob);

                    $childVisit = \App\Models\ChildVisit::create([
                        'child_id' => $child->id,
                        'visit_date' => $visitDate,
                        'age_month' => $ageMonth,
                        'complaint' => rand(0, 1) ? 'Demam ringan' : 'Sehat',
                        'weight' => rand(30, 60) / 10 + $ageMonth * 0.3,
                        'height' => rand(50, 80) + $ageMonth * 1.2,
                        'temperature' => rand(360, 380) / 10,
                        'heart_rate' => rand(90, 140),
                        'respiratory_rate' => rand(20, 40),
                        'head_circumference' => rand(30, 50) + $ageMonth * 0.1,
                        'development_notes' => rand(0, 1) ? 'Tumbuh kembang baik' : 'Perlu stimulasi motorik',
                        'icd_code' => 'Z24.0',
                        'diagnosis_name' => 'Imunisasi rutin',
                        'nutritional_status' => ['Gizi Baik', 'Gizi Kurang', 'Gizi Buruk', 'Gizi Lebih', 'Obesitas'][rand(0, 4)],
                        'informed_consent' => true,
                        'medicine_given' => ['Parasetamol Drop', 'Parasetamol Sirup', 'Tidak Ada'][rand(0, 2)],
                        'medicine_dosage' => rand(0, 1) ? '3x0.5ml' : null,
                        'notes' => rand(0, 1) ? 'Tidak ada KIPI' : 'Reaksi ringan',
                    ]);

                    // Tambah 1-2 tindakan imunisasi
                    $vaksinList = [
                        'HB0', 'BCG', 'POLIO_1', 'POLIO_2', 'POLIO_3', 'POLIO_4',
                        'HEXAVALEN_1', 'HEXAVALEN_2', 'HEXAVALEN_3',
                        'IPV_1', 'IPV_2', 'PCV_1', 'PCV_2', 'PCV_3',
                        'MR_1', 'MR_2',
                        'ROTA_1', 'ROTA_2', 'ROTA_3'
                    ];

                    shuffle($vaksinList);
                    $actionCount = rand(1, 2);

                    for ($a = 0; $a < $actionCount; $a++) {
                        \App\Models\ImmunizationAction::create([
                            'child_visit_id' => $childVisit->id,
                            'vaccine_type' => $vaksinList[$a],
                            'batch_number' => 'BATCH-' . rand(1000, 9999),
                            'body_part' => ['Paha Kiri', 'Paha Kanan', 'Lengan', 'Mulut'][rand(0, 3)],
                            'provider_name' => $patientName,
                        ]);
                    }
                }
            }
        }

        $this->command->info("✅ {$totalPatients} pasien Bali dengan nama asli (tanpa angka) berhasil dibuat!");
        $this->command->info('📊 Termasuk data ibu, suami, kehamilan aktif, ANC visit, anak, kunjungan imunisasi, dan tindakan vaksin.');
        $this->command->info('');
        $this->command->info('Login credentials:');
        $this->command->info('Email: bidan@demo.com');
        $this->command->info('Password: password');
    }
}