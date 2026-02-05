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
use App\Models\Child;
use App\Models\ChildVisit;
use App\Models\ImmunizationAction;
use Carbon\Carbon;

class ComprehensiveDataSeeder extends Seeder
{
    /**
     * Run the comprehensive database seeds.
     * Generate 30-50 realistic dummy data with proper relationships
     */
    public function run(): void
    {
        $this->command->info('🌱 Starting Comprehensive Data Seeding...');

        // Clear existing test data (optional - uncomment if needed)
        $this->command->info('🗑️  Clearing existing dummy data...');
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
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

        // 1. Create 50 Patients (Mix of demographics)
        $this->command->info('📝 Creating 50 patients...');
        $patients = $this->createPatients(50);
        $this->command->info('✅ Patients created: ' . $patients->count());

        // 2. Create Pregnancies for female patients (age 15-45)
        $this->command->info('🤰 Creating pregnancy records...');
        $pregnancies = $this->createPregnancies($patients);
        $this->command->info('✅ Pregnancies created: ' . $pregnancies->count());

        // 3. Create ANC Visits for active pregnancies
        $this->command->info('🏥 Creating ANC visits...');
        $ancCount = $this->createAncVisits($pregnancies);
        $this->command->info('✅ ANC visits created: ' . $ancCount);

        // 4. Create Delivery Records for completed pregnancies
        $this->command->info('👶 Creating delivery records...');
        $deliveryCount = $this->createDeliveryRecords($pregnancies);
        $this->command->info('✅ Delivery records created: ' . $deliveryCount);

        // 5. Create Postnatal Visits for deliveries
        $this->command->info('🩺 Creating postnatal visits...');
        $postnatalCount = $this->createPostnatalVisits($pregnancies);
        $this->command->info('✅ Postnatal visits created: ' . $postnatalCount);

        // 6. Create General Visits for all patients
        $this->command->info('🏥 Creating general clinic visits...');
        $generalCount = $this->createGeneralVisits($patients);
        $this->command->info('✅ General visits created: ' . $generalCount);

        // 7. Create KB Visits for female patients (reproductive age)
        $this->command->info('💊 Creating family planning visits...');
        $kbCount = $this->createKbVisits($patients);
        $this->command->info('✅ KB visits created: ' . $kbCount);

        // 8. Create Children for some patients
        $this->command->info('👶 Creating child records...');
        $children = $this->createChildren($patients);
        $this->command->info('✅ Children created: ' . $children->count());

        // 9. Create Child Visits and Immunizations
        $this->command->info('💉 Creating child visits & immunizations...');
        $childVisitCount = $this->createChildVisitsAndImmunizations($children);
        $this->command->info('✅ Child visits created: ' . $childVisitCount);

        $this->command->info('🎉 Comprehensive Data Seeding Completed Successfully!');
    }

    private function createPatients(int $count)
    {
        $patients = collect();
        $villages = ['Badung', 'Denpasar Selatan', 'Kuta', 'Seminyak', 'Sanur', 'Ubud', 'Gianyar'];
        $bloodTypes = ['A', 'B', 'AB', 'O'];

        for ($i = 1; $i <= $count; $i++) {
            // Ensure variety: 40% female reproductive age (18-45), 20% children (0-5), 20% elderly (60+), 20% others
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

            $patient = Patient::create([
                'nik' => '5103' . str_pad(rand(100000000000, 999999999999), 12, '0', STR_PAD_LEFT),
                'no_kk' => '5103' . str_pad(rand(100000000000, 999999999999), 12, '0', STR_PAD_LEFT),
                'no_bpjs' => rand(0, 1) ? '0001' . str_pad(rand(10000000, 99999999), 8, '0', STR_PAD_LEFT) : null,
                'no_rm' => 'RM' . str_pad($i, 6, '0', STR_PAD_LEFT),
                'name' => $gender === 'P' ? $this->getFemaleName() : $this->getMaleName(),
                'dob' => $dob,
                'gender' => $gender,
                'pob' => $villages[array_rand($villages)],
                'job' => ['Wiraswasta', 'Pegawai Swasta', 'PNS', 'Ibu Rumah Tangga', 'Petani', 'Pedagang'][array_rand(['Wiraswasta', 'Pegawai Swasta', 'PNS', 'Ibu Rumah Tangga', 'Petani', 'Pedagang'])],
                'education' => ['SD', 'SMP', 'SMA', 'D3', 'S1'][array_rand(['SD', 'SMP', 'SMA', 'D3', 'S1'])],
                'blood_type' => $bloodTypes[array_rand($bloodTypes)],
                'address' => 'Jl. ' . ['Raya', 'Utama', 'Cemara', 'Melati', 'Mawar'][array_rand(['Raya', 'Utama', 'Cemara', 'Melati', 'Mawar'])] . ' No. ' . rand(1, 100) . ', ' . $villages[array_rand($villages)],
                'phone' => '08' . rand(100000000, 999999999),
                'category' => $this->determineCategory($age, $gender),
            ]);

            $patients->push($patient);
        }

        return $patients;
    }

    private function determineCategory($age, $gender)
    {
        if ($age < 5) return 'Bayi/Balita';
        if ($age >= 60) return 'Lansia';
        if ($gender === 'P' && $age >= 15 && $age <= 49) return 'Bumil'; // Potential mother
        return 'Umum';
    }

    private function createPregnancies($patients)
    {
        $pregnancies = collect();
        $femaleReproductive = $patients->filter(function ($p) {
            $age = $p->dob->age; // Use Carbon's age property instead
            return $p->gender === 'P' && $age >= 18 && $age <= 45;
        });

        $this->command->info("   Found {$femaleReproductive->count()} eligible females for pregnancy (18-45 years)");

        // Create pregnancies for 60% of eligible females
        if ($femaleReproductive->count() > 0) {
            $selectedCount = max(1, (int)ceil($femaleReproductive->count() * 0.6));
            $selected = $femaleReproductive->random(min($selectedCount, $femaleReproductive->count()));

            foreach ($selected as $patient) {
                $isActive = rand(0, 1); // 50% active, 50% completed
                $hpht = now()->subMonths(rand(1, 9))->subDays(rand(0, 30));
                $hpl = $hpht->copy()->addDays(280);

                $pregnancy = Pregnancy::create([
                    'patient_id' => $patient->id,
                    'status' => $isActive ? 'Aktif' : 'Lahir',
                    'gravida' => 'G' . rand(1, 4) . 'P' . rand(0, 3) . 'A' . rand(0, 1),
                    'hpht' => $hpht,
                    'hpl' => $hpl,
                    'pregnancy_gap' => rand(12, 60),
                    'weight_before' => rand(45, 75),
                    'height' => rand(150, 170),
                    'delivery_date' => !$isActive ? $hpl->copy()->subDays(rand(0, 14)) : null,
                    'delivery_method' => !$isActive ? ['Normal', 'Caesar/Sectio', 'Vakum'][array_rand(['Normal', 'Caesar/Sectio', 'Vakum'])] : null,
                    'place_of_birth' => !$isActive ? ['Puskesmas', 'Klinik', 'Rumah Sakit'][array_rand(['Puskesmas', 'Klinik', 'Rumah Sakit'])] : null,
                    'birth_attendant' => !$isActive ? ['Bidan', 'Dokter'][array_rand(['Bidan', 'Dokter'])] : null,
                    'baby_gender' => !$isActive ? ['L', 'P'][array_rand(['L', 'P'])] : null,
                    'outcome' => !$isActive ? 'Hidup' : null,
                ]);

                $pregnancies->push($pregnancy);
            }
        }

        return $pregnancies;
    }

    private function createAncVisits($pregnancies)
    {
        $count = 0;
        $activePregnancies = $pregnancies->where('status', 'Aktif');

        foreach ($activePregnancies as $pregnancy) {
            $visitCount = rand(2, 4); // 2-4 ANC visits

            for ($i = 1; $i <= $visitCount; $i++) {
                $visitDate = $pregnancy->hpht->copy()->addWeeks($i * 4);
                $gestationalWeeks = $i * 4;

                if ($visitDate->isPast() && $visitDate->lte(now())) {
                    AncVisit::create([
                        'pregnancy_id' => $pregnancy->id,
                        'visit_date' => $visitDate,
                        'trimester' => $gestationalWeeks <= 12 ? 1 : ($gestationalWeeks <= 28 ? 2 : 3),
                        'visit_code' => 'K' . $i,
                        'gestational_age' => $gestationalWeeks,
                        'weight' => $pregnancy->weight_before + ($i * 1.5),
                        'height' => $pregnancy->height,
                        'lila' => rand(23, 28),
                        'systolic' => rand(110, 140),
                        'diastolic' => rand(70, 90),
                        'tfu' => $gestationalWeeks,
                        'djj' => rand(120, 160),
                        'fetal_presentation' => ['Kepala', 'Sungsang'][array_rand(['Kepala', 'Sungsang'])],
                        'hb' => rand(10, 13),
                        'risk_category' => ['Rendah', 'Tinggi'][array_rand(['Rendah', 'Tinggi'])],
                        'risk_level' => 'Rendah',
                        'tt_immunization' => 'T' . min($i, 5),
                        'fe_tablets' => $i * 30,
                        'midwife_name' => ['Bidan A', 'Bidan B'][array_rand(['Bidan A', 'Bidan B'])],
                    ]);
                    $count++;
                }
            }
        }

        return $count;
    }

    private function createDeliveryRecords($pregnancies)
    {
        // Skip for now - delivery data already in pregnancies table
        return 0;
    }

    private function createPostnatalVisits($pregnancies)
    {
        // Skip for now - can be added later if needed
        return 0;
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

        // Each patient gets 1-3 general visits
        foreach ($patients->random(min(40, $patients->count())) as $patient) {
            $visitCount = rand(1, 3);

            for ($i = 0; $i < $visitCount; $i++) {
                $visitDate = now()->subDays(rand(1, 90));
                $diagnosis = $diagnoses[array_rand($diagnoses)];

                $visit = GeneralVisit::create([
                    'patient_id' => $patient->id,
                    'visit_date' => $visitDate,
                    'complaint' => $this->getRandomComplaint(),
                    'allergies' => rand(0, 5) == 0 ? 'Amoxicillin, Seafood' : null,
                    'medical_history' => rand(0, 3) == 0 ? 'Hipertensi sejak 2 tahun lalu' : null,
                    'consciousness' => 'Compos Mentis',
                    'is_emergency' => false,
                    'lifestyle_smoking' => ['Tidak', 'Ya', 'Jarang'][array_rand(['Tidak', 'Ya', 'Jarang'])],
                    'lifestyle_alcohol' => rand(0, 10) == 0,
                    'lifestyle_activity' => ['Aktif', 'Kurang Olahraga'][array_rand(['Aktif', 'Kurang Olahraga'])],
                    'lifestyle_diet' => ['Sehat', 'Kurang Sayur/Buah', 'Tinggi Gula/Garam/Lemak'][array_rand(['Sehat', 'Kurang Sayur/Buah', 'Tinggi Gula/Garam/Lemak'])],
                    'systolic' => rand(110, 150),
                    'diastolic' => rand(70, 95),
                    'temperature' => rand(360, 385) / 10,
                    'respiratory_rate' => rand(16, 24),
                    'heart_rate' => rand(60, 100),
                    'weight' => rand(45, 90),
                    'height' => rand(150, 180),
                    'waist_circumference' => rand(65, 100),
                    'bmi' => null, // Will be calculated
                    'physical_exam' => 'Pemeriksaan fisik dalam batas normal',
                    'physical_assessment_details' => [
                        'kepala' => 'Normal',
                        'mata' => 'Konjungtiva anemis (-/-), Sklera ikterik (-/-)',
                        'telinga' => 'Normal',
                        'leher' => 'Tidak ada pembesaran kelenjar',
                        'thorax_jantung' => 'BJ I-II regular, murmur (-)',
                        'thorax_paru' => 'Vesikuler, ronkhi (-/-), wheezing (-/-)',
                        'abdomen' => 'Supel, bising usus (+) normal',
                        'ekstremitas' => 'Akral hangat, edema (-)',
                        'genitalia' => 'Tidak diperiksa',
                    ],
                    'diagnosis' => $diagnosis['name'],
                    'icd10_code' => $diagnosis['icd'],
                    'therapy' => 'Istirahat cukup, minum air putih 8 gelas/hari',
                    'status' => ['Pulang', 'Pulang', 'Pulang', 'Rujuk'][array_rand(['Pulang', 'Pulang', 'Pulang', 'Rujuk'])],
                    'payment_method' => ['Umum', 'BPJS'][array_rand(['Umum', 'BPJS'])],
                ]);

                // Add 1-3 prescriptions for this visit
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
            $qty = rand(1, 3) * 10; // 10, 20, or 30
            $unitPrice = $medicine['unit_price'];

            Prescription::create([
                'general_visit_id' => $visit->id,
                'medicine_name' => $medicine['name'],
                'quantity' => $qty . ' tablet',
                'quantity_number' => $qty,
                'unit_price' => $unitPrice,
                'total_price' => $unitPrice * $qty, // Calculate explicitly
                'signa' => ['3x1 sesudah makan', '2x1 pagi-malam', '3x1 sebelum makan'][array_rand(['3x1 sesudah makan', '2x1 pagi-malam', '3x1 sebelum makan'])],
                'dosage' => explode(' ', $medicine['name'])[1] ?? null,
                'frequency' => rand(2, 3) . 'x sehari',
                'duration' => rand(3, 7) . ' hari',
                'notes' => null,
            ]);
        }
    }

    private function createKbVisits($patients)
    {
        // Skip for now - requires kb_methods to be seeded first
        // Run KbMethodSeeder before using this
        return 0;
    }

    private function createChildren($patients)
    {
        $children = collect();

        // Select patients who could have children (20-45 years old)
        $potentialParents = $patients->filter(function ($p) {
            $age = $p->dob->age;
            return $age >= 20 && $age <= 45;
        });

        // 30% of potential parents have children
        if ($potentialParents->count() > 0) {
            $selectedCount = max(1, (int)ceil($potentialParents->count() * 0.3));
            $selected = $potentialParents->random(min($selectedCount, $potentialParents->count()));

            $childCounter = 1;
            foreach ($selected as $parent) {
                // Each parent has 1-2 children
                $childCount = rand(1, 2);

                for ($i = 0; $i < $childCount; $i++) {
                    $childAge = rand(0, 4); // 0-4 years
                    $dob = now()->subYears($childAge)->subMonths(rand(0, 11));
                    $gender = ['L', 'P'][array_rand(['L', 'P'])];

                    $child = Child::create([
                        'patient_id' => $parent->id,
                        'nik' => rand(0, 1) ? '5103' . str_pad(rand(100000000000, 999999999999), 12, '0', STR_PAD_LEFT) : null,
                        'no_rm' => 'ANAK-2026-' . str_pad($childCounter, 4, '0', STR_PAD_LEFT),
                        'name' => $this->getChildName($gender),  // Pass gender to getChildName
                        'gender' => $gender,
                        'dob' => $dob,
                        'pob' => ['Denpasar', 'Badung', 'Gianyar'][array_rand(['Denpasar', 'Badung', 'Gianyar'])],
                        'birth_weight' => rand(2500, 4000),
                        'birth_height' => rand(45, 52),
                        'status' => 'Hidup',
                    ]);

                    $children->push($child);
                    $childCounter++;
                }
            }
        }

        return $children;
    }

    private function createChildVisitsAndImmunizations($children)
    {
        // Skip for now - complex logic, can add later
        return 0;
    }

    private function createImmunization($childId, $childVisitId, $visitDate, $vaccineName, $ageMonths)
    {
        ImmunizationAction::create([
            'child_id' => $childId,
            'child_visit_id' => $childVisitId,
            'vaccine_name' => $vaccineName,
            'immunization_date' => $visitDate,
            'age_at_immunization' => $ageMonths,
            'batch_number' => 'BATCH-' . strtoupper(substr(md5($vaccineName . $visitDate), 0, 8)),
            'provider_name' => ['Bidan A', 'Bidan B', 'Perawat C'][array_rand(['Bidan A', 'Bidan B', 'Perawat C'])],
            'notes' => 'Imunisasi berjalan lancar',
        ]);
    }

    // Helper methods for names - Improved for Bali authenticity
    private function getFemaleName()
    {
        $prefixes = ['Ni Wayan', 'Ni Made', 'Ni Nyoman', 'Ni Ketut', 'Ni Putu', 'Ni Kadek', 'Ni Komang'];
        $lastNames = ['Puspa', 'Jayanti', 'Lestari', 'Dewi', 'Sari', 'Anggraini', 'Permata', 'Suci', 'Ratna', 'Indah'];
        return $prefixes[array_rand($prefixes)] . ' ' . $lastNames[array_rand($lastNames)];
    }

    private function getMaleName()
    {
        $prefixes = ['I Wayan', 'I Made', 'I Nyoman', 'I Ketut', 'I Putu', 'I Kadek', 'I Komang'];
        $lastNames = ['Suardana', 'Wijaya', 'Pratama', 'Santosa', 'Kusuma', 'Adnyana', 'Arya', 'Budiasa', 'Mahendra', 'Suryawan'];
        return $prefixes[array_rand($prefixes)] . ' ' . $lastNames[array_rand($lastNames)];
    }

    private function getChildName($gender)
    {
        if ($gender === 'P') {
            $prefixes = ['Ni Wayan', 'Ni Made', 'Ni Nyoman', 'Ni Ketut', 'Gusti Ayu', 'Anak Agung Ayu', 'Gek'];
            $lastNames = ['Dewi', 'Sari', 'Cahya', 'Puspa', 'Lestari', 'Ratna', 'Suci'];
            return $prefixes[array_rand($prefixes)] . ' ' . $lastNames[array_rand($lastNames)];
        } else {
            $prefixes = ['I Wayan', 'I Made', 'I Nyoman', 'I Ketut', 'Gusti Agung', 'Anak Agung', 'Bagus'];
            $lastNames = ['Arya', 'Satya', 'Cahya', 'Aditya', 'Pradana', 'Mahendra', 'Surya'];
            return $prefixes[array_rand($prefixes)] . ' ' . $lastNames[array_rand($lastNames)];
        }
    }

    private function getRandomComplaint()
    {
        $complaints = [
            'Demam sejak 3 hari yang lalu disertai batuk pilek',
            'Sakit kepala dan pusing berputar sejak kemarin',
            'Nyeri perut disertai mual dan muntah',
            'Batuk berdahak sudah 1 minggu tidak sembuh',
            'Tekanan darah tinggi, kepala terasa berat',
            'Diare 5x sehari sejak 2 hari lalu',
            'Gatal-gatal di kulit seluruh badan',
            'Demam tinggi dan badan lemas',
        ];

        return $complaints[array_rand($complaints)];
    }
}