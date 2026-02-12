<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Patient;
use App\Models\Pregnancy;
use App\Models\AncVisit;
use App\Models\DeliveryRecord;
use App\Models\PostnatalVisit;
use App\Models\GeneralVisit;
use App\Models\Prescription;
use App\Models\KbVisit;
use App\Models\KbMethod;
use App\Models\Child;
use App\Models\ChildVisit;
use App\Models\ChildGrowthRecord;
use App\Models\ImmunizationAction;
use App\Models\Vaccine;
use Carbon\Carbon;

class ComprehensiveDataSeeder extends Seeder
{
    /**
     * Run the comprehensive database seeds.
     * Generate 100 realistic patients with Balinese names and complete clinical data
     */
    public function run(): void
    {
        $this->command->info('🌱 Starting Comprehensive Data Seeding...');

        // Clear existing test data
        $this->command->info('🗑️  Clearing existing dummy data...');
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        ChildGrowthRecord::truncate();
        ImmunizationAction::truncate();
        ChildVisit::truncate();
        Child::truncate();
        KbVisit::truncate();
        Prescription::truncate();
        GeneralVisit::truncate();
        PostnatalVisit::truncate();
        DeliveryRecord::truncate();
        AncVisit::truncate();
        Pregnancy::truncate();
        Patient::truncate();
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        $this->command->info('✅ Previous data cleared');

        // 1. Create 100 Patients (Mix of demographics)
        $this->command->info('📝 Creating 100 patients with Balinese names...');
        $patients = $this->createPatients(100);
        $this->command->info('✅ Patients created: ' . $patients->count());

        // 2. Create Pregnancies for female patients (age 18-45)
        $this->command->info('🤰 Creating pregnancy records...');
        $pregnancies = $this->createPregnancies($patients);
        $this->command->info('✅ Pregnancies created: ' . $pregnancies->count());

        // 3. Create ANC Visits for active pregnancies
        $this->command->info('🏥 Creating ANC visits...');
        $ancCount = $this->createAncVisits($pregnancies);
        $this->command->info('✅ ANC visits created: ' . $ancCount);

        // 4. Create Delivery Records for completed pregnancies
        $this->command->info('👶 Creating delivery records...');
        $deliveryRecords = $this->createDeliveryRecords($pregnancies);
        $this->command->info('✅ Delivery records created: ' . $deliveryRecords->count());

        // 5. Create Postnatal Visits (KF1-KF4)
        $this->command->info('🩺 Creating postnatal visits (KF1-KF4)...');
        $postnatalCount = $this->createPostnatalVisits($deliveryRecords);
        $this->command->info('✅ Postnatal visits created: ' . $postnatalCount);

        // 6. Create KB Visits for female patients (reproductive age)
        $this->command->info('💊 Creating family planning visits...');
        $kbCount = $this->createKbVisits($patients);
        $this->command->info('✅ KB visits created: ' . $kbCount);

        // 7. Create Children from deliveries
        $this->command->info('👶 Creating child records from deliveries...');
        $children = $this->createChildren($deliveryRecords);
        $this->command->info('✅ Children created: ' . $children->count());

        // 8. Create Child Visits, Growth Records, and Immunizations
        $this->command->info('💉 Creating child visits, growth monitoring & immunizations...');
        $childVisitCount = $this->createChildVisitsAndImmunizations($children);
        $this->command->info('✅ Child visits created: ' . $childVisitCount);

        // 9. Create General Visits for all patients (TODO: Fix enum values)
        // $this->command->info('🏥 Creating general clinic visits...');
        // $generalCount = $this->createGeneralVisits($patients);
        // $this->command->info('✅ General visits created: ' . $generalCount);


        $this->command->info('🎉 Comprehensive Data Seeding Completed Successfully!');
    }

    private function createPatients(int $count)
    {
        $patients = collect();
        $badungAreas = ['Mengwi', 'Abiansemal', 'Petang', 'Kuta', 'Kuta Utara', 'Kuta Selatan'];
        $bloodTypes = ['A', 'B', 'AB', 'O'];

        for ($i = 1; $i <= $count; $i++) {
            // Demographics: 40% female reproductive, 20% children, 15% elderly, 25% others
            if ($i <= $count * 0.4) {
                $gender = 'P';
                $age = rand(18, 45); // Reproductive age females
            } elseif ($i <= $count * 0.5) {
                $gender = 'L';
                $age = rand(18, 45); // Males reproductive age
            } elseif ($i <= $count * 0.65) {
                $gender = $i % 2 == 0 ? 'P' : 'L';
                $age = rand(0, 5); // Children for immunization
            } elseif ($i <= $count * 0.8) {
                $gender = $i % 2 == 0 ? 'P' : 'L';
                $age = rand(60, 80); // Elderly
            } else {
                $gender = $i % 2 == 0 ? 'P' : 'L';
                $age = rand(6, 59); // Others
            }

            $dob = now()->subYears($age)->subDays(rand(0, 364));

            // Generate Balinese name based on gender and age
            $name = $gender === 'P' ? $this->getBalineseFemaleNameByAge($age) : $this->getBalineseMaleNameByAge($age);

            $patient = Patient::create([
                'nik' => '5103' . str_pad(rand(1000000000, 9999999999), 12, '0', STR_PAD_LEFT),
                'no_kk' => '5103' . str_pad(rand(1000000000, 9999999999), 12, '0', STR_PAD_LEFT),
                'no_bpjs' => rand(0, 1) ? '0001' . str_pad(rand(10000000, 99999999), 8, '0', STR_PAD_LEFT) : null,
                'no_rm' => 'RM-2026-' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'name' => $name,
                'dob' => $dob,
                'gender' => $gender,
                'pob' => $badungAreas[array_rand($badungAreas)],
                'job' => $this->getJobByAgeGender($age, $gender),
                'education' => $this->getEducationByAge($age),
                'blood_type' => $bloodTypes[array_rand($bloodTypes)],
                'address' => $this->getRealisticBadungAddress($badungAreas),
                'phone' => '08' . rand(100000000, 999999999),
                'category' => $this->determineCategory($age, $gender),
            ]);

            $patients->push($patient);
        }

        return $patients;
    }

    private function getBalineseFemaleNameByAge($age)
    {
        if ($age < 18) {
            // Children - simpler prefixes
            $prefixes = ['Ni Putu', 'Ni Made', 'Ni Nyoman', 'Ni Ketut', 'Ni Luh', 'Ni Kadek', 'Ni Komang'];
            $lastNames = ['Dewi', 'Sari', 'Ayu', 'Cahya', 'Puspa', 'Ratna', 'Widya', 'Lestari'];
        } else {
            // Adults - more formal names
            $prefixes = ['Ni Wayan', 'Ni Made', 'Ni Nyoman', 'Ni Ketut', 'Ni Luh', 'Ni Kadek', 'Ni Komang'];
            $lastNames = ['Susilawati', 'Puspitasari', 'Jayanti', 'Lestari', 'Anggraini', 'Ratna Dewi',
                         'Wulandari', 'Permatasari', 'Maharani', 'Indah Sari', 'Widya Ningrum'];
        }

        return $prefixes[array_rand($prefixes)] . ' ' . $lastNames[array_rand($lastNames)];
    }

    private function getBalineseMaleNameByAge($age)
    {
        if ($age < 18) {
            // Children - simpler prefixes
            $prefixes = ['I Putu', 'I Made', 'I Nyoman', 'I Ketut', 'I Gede', 'I Kadek', 'I Komang'];
            $lastNames = ['Arya', 'Satya', 'Cahya', 'Aditya', 'Surya', 'Pradana', 'Mahendra'];
        } else {
            // Adults - more formal names
            $prefixes = ['I Wayan', 'I Made', 'I Nyoman', 'I Ketut', 'I Gede', 'I Kadek', 'I Komang'];
            $lastNames = ['Suardana', 'Wijaya', 'Pratama', 'Santosa', 'Adnyana Kusuma', 'Budiasa',
                         'Mahendra Putra', 'Suryawan', 'Artha Wijaya', 'Satya Negara'];
        }

        return $prefixes[array_rand($prefixes)] . ' ' . $lastNames[array_rand($lastNames)];
    }

    private function getJobByAgeGender($age, $gender)
    {
        if ($age < 15) return '-';
        if ($age < 18) return 'Pelajar';

        if ($gender === 'P') {
            return ['Ibu Rumah Tangga', 'Wiraswasta', 'Pegawai Swasta', 'PNS', 'Guru', 'Petani'][array_rand(['Ibu Rumah Tangga', 'Wiraswasta', 'Pegawai Swasta', 'PNS', 'Guru', 'Petani'])];
        } else {
            return ['Wiraswasta', 'Pegawai Swasta', 'PNS', 'Buruh', 'Petani', 'Pedagang'][array_rand(['Wiraswasta', 'Pegawai Swasta', 'PNS', 'Buruh', 'Petani', 'Pedagang'])];
        }
    }

    private function getEducationByAge($age)
    {
        if ($age < 6) return 'Belum Sekolah';
        if ($age < 12) return 'SD';
        if ($age < 15) return 'SMP';
        if ($age < 18) return 'SMA';

        return ['SD', 'SMP', 'SMA', 'D3', 'S1', 'S2'][array_rand(['SD', 'SMP', 'SMA', 'D3', 'S1', 'S2'])];
    }

    private function getRealisticBadungAddress($areas)
    {
        $streets = ['Raya', 'Utama', 'Cemara', 'Melati', 'Mawar', 'Anggrek', 'Cempaka', 'Kenanga'];
        $area = $areas[array_rand($areas)];
        $street = $streets[array_rand($streets)];
        $number = rand(1, 150);

        return "Jl. {$street} No. {$number}, {$area}, Badung";
    }

    private function determineCategory($age, $gender)
    {
        if ($age < 5) return 'Bayi/Balita';
        if ($age >= 60) return 'Lansia';
        if ($gender === 'P' && $age >= 15 && $age <= 49) return 'Bumil';
        return 'Umum';
    }

    private function createPregnancies($patients)
    {
        $pregnancies = collect();
        $femaleReproductive = $patients->filter(function ($p) {
            $age = $p->dob->age;
            return $p->gender === 'P' && $age >= 18 && $age <= 45;
        });

        $this->command->info("   Found {$femaleReproductive->count()} eligible females for pregnancy");

        if ($femaleReproductive->count() > 0) {
            // 60% of eligible females have pregnancies
            $selectedCount = max(1, (int)ceil($femaleReproductive->count() * 0.6));
            $selected = $femaleReproductive->random(min($selectedCount, $femaleReproductive->count()));

            foreach ($selected as $patient) {
                // 50% active, 50% completed (delivered)
                $isActive = rand(0, 1);
                $monthsPregnant = rand(1, 9);
                $hpht = now()->subMonths($monthsPregnant)->subDays(rand(0, 30));
                $hpl = $hpht->copy()->addDays(280);

                // Realistic G/P/A based on age
                $age = $patient->dob->age;
                if ($age < 25) {
                    $gravida = rand(1, 2);
                    $para = $gravida - 1;
                    $abortus = 0;
                } elseif ($age < 35) {
                    $gravida = rand(1, 4);
                    $para = rand(0, $gravida - 1);
                    $abortus = $gravida - $para - 1;
                } else {
                    $gravida = rand(2,5);
                    $para = rand(0, $gravida - 1);
                    $abortus = min(rand(0, 2), $gravida - $para - 1);
                }

                // Format as "G#P#A#"
                $gravidaString = "G{$gravida}P{$para}A{$abortus}";

                $pregnancy = Pregnancy::create([
                    'patient_id' => $patient->id,
                    'status' => $isActive ? 'Aktif' : 'Lahir',
                    'gravida' => $gravidaString,
                    'hpht' => $hpht,
                    'hpl' => $hpl,
                    'pregnancy_gap' => $para > 0 ? rand(12, 60) : null,
                    'weight_before' => rand(45, 75),
                    'height' => rand(150, 170),
                    'is_external' => false,
                ]);

                $pregnancies->push($pregnancy);
            }
        }

        return $pregnancies;
    }

    private function createAncVisits($pregnancies)
    {
        $count = 0;
        foreach ($pregnancies as $pregnancy) {
            $weeksPregnant = $pregnancy->hpht->diffInWeeks(now());
            $visitCount = min(4, (int)ceil($weeksPregnant / 8)); // K1-K4 visits

            for ($i = 1; $i <= $visitCount; $i++) {
                $visitDate = $pregnancy->hpht->copy()->addWeeks($i * 8);
                $gestationalAge = $i * 8;

                if ($visitDate->isPast() && $visitDate->lte(now())) {
                    AncVisit::create([
                        'pregnancy_id' => $pregnancy->id,
                        'visit_date' => $visitDate,
                        'trimester' => $gestationalAge <= 12 ? 1 : ($gestationalAge <= 28 ? 2 : 3),
                        'visit_code' => 'K' . $i,
                        'gestational_age' => $gestationalAge,
                        'weight' => $pregnancy->weight_before + ($i * 1.5),
                        'height' => $pregnancy->height,
                        'lila' => rand(23, 28),
                        'systolic' => rand(110, 140),
                        'diastolic' => rand(70, 90),
                        'tfu' => $gestationalAge,
                        'djj' => rand(120, 160),
                        'fetal_presentation' => ['Kepala', 'Sungsang'][array_rand(['Kepala', 'Sungsang'])],
                        'hb' => rand(10, 13),
                        'risk_category' => 'Rendah',
                        'risk_level' => 'Rendah',
                        'tt_immunization' => 'T' . min($i, 5),
                        'fe_tablets' => $i * 30,
                        'midwife_name' => ['Bidan Ayu', 'Bidan Dewi', 'Bidan Sari'][array_rand(['Bidan Ayu', 'Bidan Dewi', 'Bidan Sari'])],
                        'service_fee' => rand(2, 4) * 25000, // 50k-100k
                    ]);
                    $count++;
                }
            }
        }

        return $count;
    }

    private function createDeliveryRecords($pregnancies)
    {
        $deliveryRecords = collect();
        $completedPregnancies = $pregnancies->where('status', 'Lahir');

        foreach ($completedPregnancies as $pregnancy) {
            $deliveryDateTime = $pregnancy->hpl->copy()->subDays(rand(-14, 14)); // ±2 weeks from HPL

            $deliveryRecord = DeliveryRecord::create([
                'pregnancy_id' => $pregnancy->id,
                'delivery_date_time' => $deliveryDateTime->copy()->setTime(rand(0, 23), rand(0, 59)),
                'gestational_age' => $pregnancy->hpht->diffInWeeks($deliveryDateTime),
                'delivery_method' => ['Spontan Belakang Kepala', 'Sectio Caesarea', 'Vakum'][array_rand(['Spontan Belakang Kepala', 'Sectio Caesarea', 'Vakum'])],
                'place_of_birth' => ['Klinik', 'Rumah Sakit', 'Puskesmas'][array_rand(['Klinik', 'Rumah Sakit', 'Puskesmas'])],
                'birth_attendant' => ['Bidan', 'Dokter SpOG'][array_rand(['Bidan', 'Dokter SpOG'])],
                'birth_weight' => rand(2500, 4000),
                'birth_length' => rand(45, 52),
                'head_circumference' => rand(32, 37),
                'gender' => ['L', 'P'][array_rand(['L', 'P'])],
                'condition' => 'Hidup',
                'apgar_score_1' => rand(7, 10),
                'apgar_score_5' => rand(8, 10),
                'congenital_defect' => null,
                'placenta_delivery' => 'Spontan',
                'perineum_rupture' => ['Utuh', 'Derajat 1', 'Derajat 2'][array_rand(['Utuh', 'Derajat 1', 'Derajat 2'])],
                'bleeding_amount' => rand(100, 300),
                'blood_pressure' => rand(110, 130) . '/' . rand(70, 85),
                'imd_initiated' => true,
                'vit_k_given' => true,
                'eye_ointment_given' => true,
                'hb0_given' => true,
                'duration_first_stage' => rand(4, 12) . ' jam',
                'duration_second_stage' => rand(15, 60) . ' menit',
            ]);

            $deliveryRecords->push($deliveryRecord);
        }

        return $deliveryRecords;
    }

    private function createPostnatalVisits($deliveryRecords)
    {
        $count = 0;

        foreach ($deliveryRecords as $delivery) {
            // KF1-KF4 visits (6-48 hours, 4-7 days, 8-28 days, 29-42 days)
            $kfSchedule = [
                ['code' => 'KF1', 'minDays' => 0, 'maxDays' => 2],
                ['code' => 'KF2', 'minDays' => 4, 'maxDays' => 7],
                ['code' => 'KF3', 'minDays' => 8, 'maxDays' => 28],
                ['code' => 'KF4', 'minDays' => 29, 'maxDays' => 42],
            ];

            foreach ($kfSchedule as $index => $kf) {
                $visitDate = $delivery->delivery_date_time->copy()->addDays(rand($kf['minDays'], $kf['maxDays']));
                $daysPostpartum = $delivery->delivery_date_time->diffInDays($visitDate);

                // Only create if visit date is in the past
                if ($visitDate->isPast() && $visitDate->lte(now())) {
                    PostnatalVisit::create([
                        'pregnancy_id' => $delivery->pregnancy_id,
                        'visit_date' => $visitDate,
                        'visit_code' => $kf['code'],
                        'temperature' => rand(360, 372) / 10,
                        'td_systolic' => rand(110, 130),
                        'td_diastolic' => rand(70, 85),
                        'lochea' => $this->getLocheaByDays($daysPostpartum),
                        'uterine_involution' => $this->getUterineInvolution($daysPostpartum),
                        'vitamin_a' => $index == 0, // KF1 only
                        'fe_tablets' => rand(0, 30),
                        'complication_check' => true,
                        'conclusion' => 'Ibu sehat, nifas berjalan normal',
                        'service_fee' => rand(2, 3) * 25000, // 50k-75k
                    ]);
                    $count++;
                }
            }
        }

        return $count;
    }

    private function getLocheaByDays($days)
    {
        if ($days <= 3) return 'Rubra';
        if ($days <= 7) return 'Sanguinolenta';
        if ($days <= 14) return 'Serosa';
        return 'Alba';
    }


    private function getUterineInvolution($days)
    {
        if ($days == 0) return 'Setinggi Pusat';
        if ($days <= 7) return ((12 - $days) . ' cm di bawah pusat');
        return 'Tidak teraba';
    }

    private function createKbVisits()
    {
        $count = 0;
        $kbMethods = KbMethod::all();
        $patients = Patient::where('gender', 'P')
            ->whereBetween('dob', [now()->subYears(45), now()->subYears(18)])
            ->inRandomOrder()
            ->limit(20)
            ->get();

        foreach ($patients as $patient) {
            $kbMethod = $kbMethods->random();
            $visitCount = rand(1, 3);

            for ($i = 0; $i < $visitCount; $i++) {
                $visitDate = now()->subMonths($visitCount - $i)->subDays(rand(0, 28));
                $isLastVisit = ($i == $visitCount - 1);

                KbVisit::create([
                    'patient_id' => $patient->id,
                    'kb_method_id' => $kbMethod->id,
                    'visit_date' => $visitDate,
                    'no_rm' => $patient->no_rm,
                    'visit_type' => $i == 0 ? 'Peserta Baru' : 'Peserta Lama',
                    'contraception_brand' => $this->getContraceptionBrand($kbMethod->name),
                    'blood_pressure_systolic' => rand(110, 140),
                    'blood_pressure_diastolic' => rand(70, 90),
                    'weight' => rand(45, 75),
                    'side_effects' => rand(0, 10) == 0 ? 'Spotting ringan' : null,
                    'complications' => null,
                    'physical_exam_notes' => 'Pemeriksaan umum baik',
                    'next_visit_date' => $isLastVisit ? $visitDate->copy()->addMonths($this->getKbInterval($kbMethod->name)) : null,
                    'payment_type' => ['BPJS', 'Umum'][array_rand(['BPJS', 'Umum'])],
                    'service_fee' => rand(2, 4) * 25000,
                    'midwife_name' => ['Bidan Ayu Saraswati', 'Bidan Dewi Lestari'][array_rand(['Bidan Ayu Saraswati', 'Bidan Dewi Lestari'])],
                ]);
                $count++;
            }
        }

        return $count;
    }

    private function getContraceptionBrand($methodName)
    {
        $brands = [
            'Pil KB' => ['Microgynon', 'Nordette', 'Yasmin'],
            'Suntik 1 Bulan' => ['Cyclofem', 'Mesigyna'],
            'Suntik 3 Bulan' => ['Depo Provera', 'Depo Geston'],
            'Implan' => ['Implanon', 'Jadena'],
            'IUD/Spiral' => ['Copper T380A', 'Multiload'],
        ];

        foreach ($brands as $method => $brandList) {
            if (str_contains($methodName, $method)) {
                return $brandList[array_rand($brandList)];
            }
        }

        return null;
    }

    private function getKbInterval($methodName)
    {
        if (str_contains($methodName,'Pil')) return 1;
        if (str_contains($methodName, '1 Bulan')) return 1;
        if (str_contains($methodName, '3 Bulan')) return 3;
        if (str_contains($methodName, 'Implan')) return 36; // 3 years
        if (str_contains($methodName, 'IUD')) return 60; // 5 years
        return 3;
    }

    private function createChildren($deliveryRecords)
    {
        $children = collect();
        $childCounter = 1;

        foreach ($deliveryRecords as $delivery) {
            // Create child from delivery
            $pregnancy = $delivery->pregnancy;
            $mother = $pregnancy->patient;

            $child = Child::create([
                'patient_id' => $mother->id,
                'nik' => rand(0, 1) ? '5103' . str_pad(rand(1000000000, 9999999999), 12, '0', STR_PAD_LEFT) : null,
                'no_rm' => 'ANAK-2026-' . str_pad($childCounter, 4, '0',STR_PAD_LEFT),
                'name' => $this->getChildName($delivery->gender),
                'gender' => $delivery->gender,
                'dob' => $delivery->delivery_date_time,
                'pob' => $mother->pob,
                'birth_weight' => $delivery->birth_weight,
                'birth_height' => $delivery->birth_length,
                'status' => 'Hidup',
            ]);

            $children->push($child);
            $childCounter++;
        }

        return $children;
    }

    private function getChildName($gender)
    {
        if ($gender === 'P') {
            $prefixes = ['Ni Putu', 'Ni Made', 'Ni Nyoman', 'Ni Ketut', 'Ni Luh', 'Gek'];
            $lastNames = ['Dewi', 'Sari', 'Ayu', 'Cahya', 'Puspa', 'Ratna', 'Widya'];
            return $prefixes[array_rand($prefixes)] . ' ' . $lastNames[array_rand($lastNames)];
        } else {
            $prefixes = ['I Putu', 'I Made', 'I Nyoman', 'I Ketut', 'I Gede', 'Bagus'];
            $lastNames = ['Arya', 'Satya', 'Cahya', 'Aditya', 'Surya', 'Pradana'];
            return $prefixes[array_rand($prefixes)] . ' ' . $lastNames[array_rand($lastNames)];
        }
    }

    private function createChildVisitsAndImmunizations($children)
    {
        $count = 0;
        $vaccines = Vaccine::all();

        if ($vaccines->isEmpty()) {
            $this->command->warn('   No vaccines found. Skipping immunizations.');
        }

        foreach ($children as $child) {
            $ageMonths = $child->dob->diffInMonths(now());
            $visitCount = min(6, $ageMonths + 1); // Up to 6 visits in first year

            for ($i = 0; $i < $visitCount; $i++) {
                $visitDate = $child->dob->copy()->addMonths($i)->addDays(rand(0, 14));
                $ageAtVisit = $child->dob->diffInMonths($visitDate);

                if ($visitDate->isPast() && $visitDate->lte(now())) {
                    $childVisit = ChildVisit::create([
                        'child_id' => $child->id,
                        'visit_date' => $visitDate,
                        'age_months' => $ageAtVisit,
                        'weight' => $child->birth_weight + ($ageAtVisit * rand(500, 700)),
                        'height' => $child->birth_height + ($ageAtVisit * rand(2, 3)),
                        'head_circumference' => 34 + ($ageAtVisit * 0.5),
                        'exclusive_breastfeeding' => $ageAtVisit < 6,
                        'vitamin_a_given' => $ageAtVisit >= 6 && $ageAtVisit % 6 == 0,
                        'notes' => 'Tumbuh kembang normal',
                        'service_fee' => rand(1, 2) * 25000, // 25k-50k
                    ]);

                    // Add growth record
                    ChildGrowthRecord::create([
                        'child_visit_id' => $childVisit->id,
                        'measurement_date' => $visitDate,
                        'age_months' => $ageAtVisit,
                        'weight' => $childVisit->weight / 1000, // Convert to kg
                        'height' => $childVisit->height,
                        'head_circumference' => $childVisit->head_circumference,
                    ]);

                    // Add age-appropriate immunizations
                    if (!$vaccines->isEmpty()) {
                        $this->createImmunizationsForAge($child->id, $childVisit->id, $visitDate, $ageAtVisit, $vaccines);
                    }

                    $count++;
                }
            }
        }

        return $count;
    }

    private function createImmunizationsForAge($childId, $visitId, $visitDate, $ageMonths, $vaccines)
    {
        // Simplified immunization schedule
        $schedule = [
            0 => ['HB 0', 'BCG', 'Polio 1'],
            2 => ['DPT-HB-Hib 1', 'Polio 2'],
            3 => ['DPT-HB-Hib 2', 'Polio 3'],
            4 => ['DPT-HB-Hib 3', 'Polio 4', 'IPV'],
            9 => ['Campak'],
            18 => ['DPT-HB-Hib Booster', 'Campak Booster'],
        ];

        if (isset($schedule[$ageMonths])) {
            foreach ($schedule[$ageMonths] as $vaccineName) {
                $vaccine = $vaccines->where('name', $vaccineName)->first();

                if ($vaccine) {
                    ImmunizationAction::create([
                        'child_id' => $childId,
                        'child_visit_id' => $visitId,
                        'vaccine_id' => $vaccine->id,
                        'vaccine_name' => $vaccine->name,
                        'immunization_date' => $visitDate,
                        'age_at_immunization' => $ageMonths,
                        'batch_number' => 'BATCH-' . strtoupper(substr(md5($vaccineName . $visitDate), 0, 8)),
                        'provider_name' => ['Bidan Ayu', 'Bidan Dewi', 'Perawat Sari'][array_rand(['Bidan Ayu', 'Bidan Dewi', 'Perawat Sari'])],
                        'notes' => 'Imunisasi sesuai jadwal',
                    ]);
                }
            }
        }
    }

    private function createGeneralVisits($patients)
    {
        $count = 0;
        $diagnoses = [
            ['name' => 'ISPA (Infeksi Saluran Pernafasan Akut)', 'icd' => 'J06.9'],
            ['name' => 'Hipertensi Esensial', 'icd' => 'I10'],
            ['name' => 'Gastritis', 'icd' => 'K29.7'],
            ['name' => 'Diabetes Mellitus Tipe 2', 'icd' => 'E11.9'],
            ['name' => 'Demam', 'icd' => 'R50.9'],
            ['name' => 'Sakit Kepala', 'icd' => 'R51'],
            ['name' => 'Diare Akut', 'icd' => 'A09'],
        ];

        // 40% of patients have 1-3 general visits
        $selectedPatients = $patients->random(min(40, $patients->count()));

        foreach ($selectedPatients as $patient) {
            $visitCount = rand(1, 3);

            for ($i = 0; $i < $visitCount; $i++) {
                $visitDate = now()->subDays(rand(1, 90));
                $diagnosis = $diagnoses[array_rand($diagnoses)];

                $visit = GeneralVisit::create([
                    'patient_id' => $patient->id,
                    'visit_date' => $visitDate,
                    'complaint' => $this->getRandomComplaint(),
                    'allergies' => rand(0, 10) == 0 ? 'Amoxicillin' : null,
                    'medical_history' => rand(0, 5) == 0 ? 'Hipertensi' : null,
                    'consciousness' => 'Compos Mentis',
                    'is_emergency' => false,
                    'lifestyle_smoking' => 'Tidak',
                    'lifestyle_alcohol' => false,
                    'lifestyle_activity' => ['Aktif', 'Kurang Aktif'][array_rand(['Aktif', 'Kurang Aktif'])],
                    'lifestyle_diet' => 'Sehat',
                    'systolic' => rand(110, 150),
                    'diastolic' => rand(70, 95),
                    'temperature' => rand(360, 385) / 10,
                    'respiratory_rate' => rand(16, 24),
                    'heart_rate' => rand(60, 100),
                    'weight' => rand(45, 90),
                    'height' => rand(150, 180),
                    'waist_circumference' => rand(65, 100),
                    'physical_exam' => 'Dalam batas normal',
                    'diagnosis' => $diagnosis['name'],
                    'icd10_code' => $diagnosis['icd'],
                    'therapy' => 'Istirahat cukup, minum obat teratur',
                    'status' => 'Pulang',
                    'payment_method' => ['Umum', 'BPJS'][array_rand(['Umum', 'BPJS'])],
                ]);

                // Add 1-3 prescriptions
                $this->createPrescriptionsForVisit($visit, rand(1, 3));
                $count++;
            }
        }

        return $count;
    }

    private function createPrescriptionsForVisit($visit, $count)
    {
        $medicines = [
            ['name' => 'Paracetamol 500mg', 'unit_price' => 1500],
            ['name' => 'Amoxicillin 500mg', 'unit_price' => 3000],
            ['name' => 'OBH Combi Syrup', 'unit_price' => 15000],
            ['name' => 'Antasida Tablet', 'unit_price' => 2000],
            ['name' => 'Vitamin C 1000mg', 'unit_price' => 5000],
            ['name' => 'Metformin 500mg', 'unit_price' => 2500],
            ['name' => 'Captopril 25mg', 'unit_price' => 1800],
        ];

        for ($i = 0; $i < $count; $i++) {
            $medicine = $medicines[array_rand($medicines)];
            $qty = rand(1, 3) * 10;
            $unitPrice = $medicine['unit_price'];

            Prescription::create([
                'general_visit_id' => $visit->id,
                'medicine_name' => $medicine['name'],
                'quantity' => $qty . ' tablet',
                'quantity_number' => $qty,
                'unit_price' => $unitPrice,
                'total_price' => $unitPrice * $qty,
                'signa' => '3x1 sesudah makan',
                'dosage' => explode(' ', $medicine['name'])[1] ?? null,
                'frequency' => '3x sehari',
                'duration' => rand(3, 7) . ' hari',
                'notes' => null,
            ]);
        }
    }

    private function getRandomComplaint()
    {
        return [
            'Demam sejak 3 hari disertai batuk pilek',
            'Sakit kepala dan pusing',
            'Nyeri perut disertai mual',
            'Batuk berdahak sudah 1 minggu',
            'Tekanan darah tinggi',
            'Diare 5x sehari sejak 2 hari lalu',
            'Demam tinggi dan badan lemas',
        ][rand(0, 6)];
    }
}