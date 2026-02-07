<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Patient;
use App\Models\Pregnancy;
use App\Models\AncVisit;
use App\Models\DeliveryRecord;
use App\Models\PostnatalVisit;
use App\Models\Child;
use App\Models\KbVisit;
use App\Models\KbMethod;
use Illuminate\Support\Facades\DB;

class TargetedPatientSeeder extends Seeder
{
    // Nama-nama Bali yang realistis
    private array $namaWanitaBali = [
        'Ni Made Dewi Sartika',
        'Ni Ketut Ayu Lestari',
        'Ni Wayan Sri Wahyuni',
        'Ni Putu Eka Pratiwi',
        'Ni Kadek Ratna Sari',
        'Ni Komang Suci Rahayu',
        'Ni Luh Putu Indah',
        'Ni Made Ari Kusuma',
        'Ni Wayan Citra Dewi',
        'Ni Ketut Rani Paramita',
        'Ni Made Puspa Wati',
        'Ni Putu Ayu Kartini',
        'Ni Kadek Wulan Sari',
        'Ni Wayan Mega Putri',
        'Ni Komang Trisna Dewi',
        'Ni Luh Gede Astiti',
        'Ni Made Yuni Artini',
        'Ni Ketut Sinta Pertiwi',
        'Ni Putu Ratih Kumala',
        'Ni Wayan Murni Asih',
        'Ni Made Candra Dewi',
        'Ni Kadek Bulan Sari',
        'Ni Komang Widi Astuti',
        'Ni Luh Made Suryani',
        'Ni Putu Novi Antari',
    ];

    private array $namaPriaBali = [
        'I Made Agus Wirawan',
        'I Wayan Sudirman',
        'I Ketut Oka Mahendra',
        'I Putu Gede Astawa',
        'I Kadek Yoga Pratama',
        'I Komang Ari Widnyana',
        'I Made Bayu Segara',
        'I Wayan Dharma Putra',
        'I Ketut Surya Negara',
        'I Putu Adi Suartana',
    ];

    private array $desaBali = [
        'Br. Tegal, Desa Mengwi, Kec. Mengwi',
        'Br. Kaja, Desa Kapal, Kec. Mengwi',
        'Br. Tengah, Desa Abiansemal, Kec. Abiansemal',
        'Br. Dinas, Desa Sempidi, Kec. Mengwi',
        'Br. Kangin, Desa Lukluk, Kec. Mengwi',
        'Br. Delod, Desa Darmasaba, Kec. Abiansemal',
        'Br. Kawan, Desa Sibang, Kec. Abiansemal',
        'Br. Desa, Desa Punggul, Kec. Abiansemal',
        'Br. Pande, Desa Munggu, Kec. Mengwi',
        'Br. Gede, Desa Cemagi, Kec. Mengwi',
        'Br. Saba, Desa Sading, Kec. Mengwi',
        'Br. Uma, Desa Buduk, Kec. Mengwi',
        'Br. Anyar, Desa Baha, Kec. Mengwi',
        'Br. Bindu, Desa Kekeran, Kec. Mengwi',
        'Br. Pandak, Desa Tumbakbayuh, Kec. Mengwi',
    ];

    private array $pekerjaanWanita = [
        'Ibu Rumah Tangga',
        'Pedagang',
        'Petani',
        'Guru',
        'Pegawai Swasta',
        'Wiraswasta',
        'Buruh',
        'PNS',
    ];

    private array $pekerjaanPria = [
        'Petani',
        'Pedagang',
        'Buruh Bangunan',
        'Sopir',
        'Pegawai Swasta',
        'Wiraswasta',
        'PNS',
        'Nelayan',
        'Tukang',
    ];

    private int $nameIndex = 0;
    private int $maleNameIndex = 0;

    public function run(): void
    {
        $this->command->info('🎯 Starting Targeted Patient Seeding (Realistic Bali Data)...');

        // Verify KB Methods exist for KB scenario
        if (KbMethod::count() === 0) {
            $this->call(KbMethodSeeder::class);
        }

        DB::transaction(function () {
            // 1. Kategori: IBU HAMIL AKTIF (Trimester 1, 2, 3) - 9 pasien
            $this->createBumilAktif();

            // 2. Kategori: IBU HAMIL RISTI (Resiko Tinggi) - 6 pasien
            $this->createBumilRisti();

            // 3. Kategori: IBU BERSALIN (Baru Melahirkan) - 4 pasien
            $this->createIbuBersalin();

            // 4. Kategori: IBU NIFAS (Pasca Salin + Kunjungan Nifas) - 4 pasien
            $this->createIbuNifas();

            // 5. Kategori: ANAK / BAYI (Internal & External) - 8 anak
            $this->createAnak();

            // 6. Kategori: AKSEPTOR KB - 6 pasien
            $this->createAkseptorKB();

            // 7. Kategori: PASIEN UMUM - 5 pasien
            $this->createPasienUmum();
        });

        $this->command->info('✅ Targeted Patient Seeding Completed! (30-45 patients created)');
    }

    private function getNextWomanName(): string
    {
        $name = $this->namaWanitaBali[$this->nameIndex % count($this->namaWanitaBali)];
        $this->nameIndex++;
        return $name;
    }

    private function getNextManName(): string
    {
        $name = $this->namaPriaBali[$this->maleNameIndex % count($this->namaPriaBali)];
        $this->maleNameIndex++;
        return $name;
    }

    private function getRandomAddress(): string
    {
        return $this->desaBali[array_rand($this->desaBali)];
    }

    private function getRandomWomanJob(): string
    {
        return $this->pekerjaanWanita[array_rand($this->pekerjaanWanita)];
    }

    private function getRandomManJob(): string
    {
        return $this->pekerjaanPria[array_rand($this->pekerjaanPria)];
    }

    private function generateNIK(): string
    {
        // Format NIK Bali: 5103 (Badung) + DDMMYY + 4 digit random
        return '5103' . sprintf('%02d', rand(1, 28)) . sprintf('%02d', rand(1, 12)) . sprintf('%02d', rand(85, 99)) . sprintf('%04d', rand(1, 9999));
    }

    private function generatePhone(): string
    {
        $prefixes = ['0812', '0813', '0821', '0822', '0857', '0858', '0878', '0877'];
        return $prefixes[array_rand($prefixes)] . rand(10000000, 99999999);
    }

    private function createBumilAktif()
    {
        $this->command->info('   🤰 Seeding Bumil Aktif (9 pasien)...');

        // Trimester 1 - 3 pasien
        for ($i = 0; $i < 3; $i++) {
            $weeks = rand(6, 12);
            $this->createPregnantPatient($weeks, 1);
        }

        // Trimester 2 - 3 pasien
        for ($i = 0; $i < 3; $i++) {
            $weeks = rand(14, 26);
            $this->createPregnantPatient($weeks, 2);
        }

        // Trimester 3 - 3 pasien
        for ($i = 0; $i < 3; $i++) {
            $weeks = rand(28, 38);
            $this->createPregnantPatient($weeks, 3);
        }
    }

    private function createPregnantPatient(int $weeks, int $trimester): void
    {
        $hpht = now()->subWeeks($weeks);
        $age = rand(20, 34); // Usia normal kehamilan
        $husbandName = $this->getNextManName();

        $patient = Patient::create([
            'name' => $this->getNextWomanName(),
            'nik' => $this->generateNIK(),
            'no_rm' => 'RM-' . now()->format('Ymd') . '-' . str_pad(Patient::count() + 1, 4, '0', STR_PAD_LEFT),
            'dob' => now()->subYears($age)->subDays(rand(0, 365)),
            'gender' => 'P',
            'pob' => 'Denpasar',
            'address' => $this->getRandomAddress(),
            'phone' => $this->generatePhone(),
            'job' => $this->getRandomWomanJob(),
            'blood_type' => ['A', 'B', 'AB', 'O'][array_rand(['A', 'B', 'AB', 'O'])],
            'husband_name' => $husbandName,
            'husband_job' => $this->getRandomManJob(),
            'category' => 'Bumil',
        ]);

        $gravida = ['G1P0A0', 'G2P1A0', 'G3P2A0', 'G2P0A1'][array_rand(['G1P0A0', 'G2P1A0', 'G3P2A0', 'G2P0A1'])];
        $weightBefore = rand(45, 65);
        $height = rand(150, 165);

        $pregnancy = Pregnancy::create([
            'patient_id' => $patient->id,
            'status' => 'Aktif',
            'gravida' => $gravida,
            'hpht' => $hpht,
            'hpl' => $hpht->copy()->addDays(280),
            'weight_before' => $weightBefore,
            'height' => $height,
            'pregnancy_gap' => $gravida === 'G1P0A0' ? null : rand(2, 5),
        ]);

        // Create ANC visit
        $visitCode = $trimester === 1 ? 'K1' : ($trimester === 2 ? 'K2' : ['K3', 'K4'][array_rand(['K3', 'K4'])]);
        $currentWeight = $weightBefore + ($weeks * 0.4); // Kenaikan BB normal

        AncVisit::create([
            'pregnancy_id' => $pregnancy->id,
            'visit_date' => now()->subDays(rand(0, 14)),
            'trimester' => $trimester,
            'gestational_age' => $weeks,
            'visit_code' => $visitCode,
            'weight' => $currentWeight,
            'height' => $height,
            'lila' => rand(235, 280) / 10, // 23.5 - 28.0 cm (normal)
            'systolic' => rand(100, 130),
            'diastolic' => rand(60, 85),
            'tfu' => $weeks > 20 ? $weeks - rand(0, 2) : null,
            'djj' => $weeks > 12 ? rand(120, 160) : null,
            'hb' => rand(100, 130) / 10, // 10.0 - 13.0 g/dL
            'midwife_name' => 'Bidan ' . ['Ayu', 'Dewi', 'Sari', 'Wati'][array_rand(['Ayu', 'Dewi', 'Sari', 'Wati'])],
            'risk_category' => 'Rendah',
        ]);
    }

    private function createBumilRisti()
    {
        $this->command->info('   🚨 Seeding Bumil Risti (6 pasien)...');

        // Risti Usia Tua (> 35 tahun) - 2 pasien
        for ($i = 0; $i < 2; $i++) {
            $this->createRistiPatient('usia_tua');
        }

        // Risti KEK (LILA < 23.5) - 2 pasien
        for ($i = 0; $i < 2; $i++) {
            $this->createRistiPatient('kek');
        }

        // Risti Hipertensi - 2 pasien
        for ($i = 0; $i < 2; $i++) {
            $this->createRistiPatient('hipertensi');
        }
    }

    private function createRistiPatient(string $type): void
    {
        $weeks = rand(16, 32);
        $hpht = now()->subWeeks($weeks);

        // Set age based on risk type
        $age = match($type) {
            'usia_tua' => rand(36, 42),
            default => rand(18, 22),
        };

        $husbandName = $this->getNextManName();

        $patient = Patient::create([
            'name' => $this->getNextWomanName(),
            'nik' => $this->generateNIK(),
            'no_rm' => 'RM-' . now()->format('Ymd') . '-' . str_pad(Patient::count() + 1, 4, '0', STR_PAD_LEFT),
            'dob' => now()->subYears($age)->subDays(rand(0, 365)),
            'gender' => 'P',
            'address' => $this->getRandomAddress(),
            'phone' => $this->generatePhone(),
            'job' => $this->getRandomWomanJob(),
            'husband_name' => $husbandName,
            'husband_job' => $this->getRandomManJob(),
            'category' => 'Bumil',
        ]);

        $pregnancy = Pregnancy::create([
            'patient_id' => $patient->id,
            'status' => 'Aktif',
            'gravida' => $type === 'usia_tua' ? 'G4P3A0' : 'G1P0A0',
            'hpht' => $hpht,
            'hpl' => $hpht->copy()->addDays(280),
            'weight_before' => $type === 'kek' ? rand(38, 45) : rand(50, 60),
            'height' => rand(148, 155),
            'risk_score_initial' => rand(6, 10),
        ]);

        // Risk-specific data
        $lila = match($type) {
            'kek' => rand(200, 234) / 10, // < 23.5 KEK
            default => rand(235, 280) / 10,
        };

        $systolic = match($type) {
            'hipertensi' => rand(140, 160),
            default => rand(100, 130),
        };

        $diastolic = match($type) {
            'hipertensi' => rand(90, 100),
            default => rand(60, 85),
        };

        $trimester = $weeks <= 12 ? 1 : ($weeks <= 28 ? 2 : 3);

        AncVisit::create([
            'pregnancy_id' => $pregnancy->id,
            'visit_date' => now()->subDays(rand(0, 7)),
            'gestational_age' => $weeks,
            'visit_code' => 'K1',
            'trimester' => $trimester,
            'lila' => $lila,
            'systolic' => $systolic,
            'diastolic' => $diastolic,
            'hb' => $type === 'kek' ? rand(80, 99) / 10 : rand(100, 120) / 10, // Anemia jika KEK
            'midwife_name' => 'Bidan Dewi',
            'risk_category' => 'Tinggi',
        ]);
    }

    private function createIbuBersalin()
    {
        $this->command->info('   👶 Seeding Ibu Bersalin (4 pasien)...');

        $deliveryMethods = ['Spontan Belakang Kepala', 'Spontan Belakang Kepala', 'Sectio Caesarea', 'Vakum'];
        $babyGenders = ['L', 'P', 'L', 'P'];

        for ($i = 0; $i < 4; $i++) {
            $husbandName = $this->getNextManName();
            $hoursAgo = rand(2, 72);

            $patient = Patient::create([
                'name' => $this->getNextWomanName(),
                'nik' => $this->generateNIK(),
                'no_rm' => 'RM-' . now()->format('Ymd') . '-' . str_pad(Patient::count() + 1, 4, '0', STR_PAD_LEFT),
                'dob' => now()->subYears(rand(22, 32))->subDays(rand(0, 365)),
                'gender' => 'P',
                'address' => $this->getRandomAddress(),
                'phone' => $this->generatePhone(),
                'husband_name' => $husbandName,
                'husband_job' => $this->getRandomManJob(),
                'category' => 'Bumil',
            ]);

            $pregnancy = Pregnancy::create([
                'patient_id' => $patient->id,
                'status' => 'Aktif',
                'gravida' => ['G1P0A0', 'G2P1A0', 'G1P0A0', 'G3P2A0'][$i],
                'hpht' => now()->subWeeks(40),
                'hpl' => now(),
            ]);

            // Baby naming convention: By. Ny. [Mother's short name]
            $motherShortName = explode(' ', $patient->name);
            $babyName = 'By. Ny. ' . end($motherShortName);

            DeliveryRecord::create([
                'pregnancy_id' => $pregnancy->id,
                'delivery_date_time' => now()->subHours($hoursAgo),
                'gestational_age' => rand(38, 41),
                'delivery_method' => $deliveryMethods[$i],
                'birth_attendant' => $deliveryMethods[$i] === 'Sectio Caesarea' ? 'dr. I Wayan Surya, Sp.OG' : 'Bidan Ayu Paramita',
                'place_of_birth' => $deliveryMethods[$i] === 'Sectio Caesarea' ? 'RSUD Badung' : 'Klinik Pratama Sehat',
                'placenta_delivery' => 'Spontan',
                'perineum_rupture' => ['Utuh', 'Derajat 1', 'Utuh', 'Derajat 2'][$i],
                'bleeding_amount' => rand(100, 300),
                'baby_name' => $babyName,
                'gender' => $babyGenders[$i],
                'birth_weight' => rand(2800, 3800),
                'birth_length' => rand(48, 52),
                'head_circumference' => rand(32, 36),
                'apgar_score_1' => rand(7, 9),
                'apgar_score_5' => rand(8, 10),
                'condition' => 'Hidup',
                'imd_initiated' => true,
                'vit_k_given' => true,
                'eye_ointment_given' => true,
                'hb0_given' => true,
            ]);
        }
    }

    private function createIbuNifas()
    {
        $this->command->info('   🩺 Seeding Ibu Nifas (4 pasien)...');

        $visitCodes = ['KF1', 'KF2', 'KF3', 'KF4'];

        for ($i = 0; $i < 4; $i++) {
            $daysPostpartum = match($visitCodes[$i]) {
                'KF1' => rand(1, 2),
                'KF2' => rand(3, 7),
                'KF3' => rand(8, 28),
                'KF4' => rand(29, 42),
            };
            $deliveryDate = now()->subDays($daysPostpartum);
            $husbandName = $this->getNextManName();

            $patient = Patient::create([
                'name' => $this->getNextWomanName(),
                'nik' => $this->generateNIK(),
                'no_rm' => 'RM-' . now()->format('Ymd') . '-' . str_pad(Patient::count() + 1, 4, '0', STR_PAD_LEFT),
                'dob' => now()->subYears(rand(24, 35))->subDays(rand(0, 365)),
                'gender' => 'P',
                'address' => $this->getRandomAddress(),
                'phone' => $this->generatePhone(),
                'husband_name' => $husbandName,
                'category' => 'Bumil',
            ]);

            $pregnancy = Pregnancy::create([
                'patient_id' => $patient->id,
                'status' => 'Aktif',
                'gravida' => ['G1P0A0', 'G2P1A0', 'G3P2A0', 'G2P1A0'][$i],
                'hpht' => $deliveryDate->copy()->subWeeks(39),
                'hpl' => $deliveryDate->copy()->addWeeks(1),
            ]);

            // Trigger Observer
            DeliveryRecord::create([
                'pregnancy_id' => $pregnancy->id,
                'delivery_date_time' => $deliveryDate,
                'gestational_age' => rand(38, 40),
                'delivery_method' => ['Spontan Belakang Kepala', 'Sectio Caesarea', 'Spontan Belakang Kepala', 'Spontan Belakang Kepala'][$i],
                'birth_attendant' => 'Bidan Pratama',
                'place_of_birth' => 'Klinik',
                'baby_name' => 'By. ' . explode(' ', $patient->name)[2],
                'gender' => ['L', 'P', 'L', 'P'][$i],
                'birth_weight' => rand(2900, 3600),
                'birth_length' => rand(48, 51),
                'condition' => 'Hidup',
            ]);

            // Create Postnatal Visit
            PostnatalVisit::create([
                'pregnancy_id' => $pregnancy->id,
                'visit_date' => now()->subDays(rand(0, 3)),
                'visit_code' => $visitCodes[$i],
                'td_systolic' => rand(100, 120),
                'td_diastolic' => rand(60, 80),
                'temperature' => rand(365, 375) / 10,
                'lochea' => ['Rubra', 'Sanguinolenta', 'Serosa', 'Alba'][$i],
                'uterine_involution' => ['Setinggi Pusat', 'Pertengahan', '2 Jari Atas Simfisis', 'Tidak Teraba'][$i],
                'vitamin_a' => $i >= 1,
                'fe_tablets' => $i >= 1 ? 30 : 0,
                'complication_check' => false,
                'conclusion' => 'Sehat',
            ]);
        }
    }

    private function createAnak()
    {
        $this->command->info('   👶 Seeding Anak (8 anak)...');

        // 4 Anak Internal (dengan ibu terdaftar)
        for ($i = 0; $i < 4; $i++) {
            $mother = Patient::create([
                'name' => $this->getNextWomanName(),
                'nik' => $this->generateNIK(),
                'no_rm' => 'RM-' . now()->format('Ymd') . '-' . str_pad(Patient::count() + 1, 4, '0', STR_PAD_LEFT),
                'dob' => now()->subYears(rand(25, 38))->subDays(rand(0, 365)),
                'gender' => 'P',
                'address' => $this->getRandomAddress(),
                'phone' => $this->generatePhone(),
                'category' => 'Umum',
            ]);

            $childAge = rand(1, 48); // 1-48 bulan
            $gender = ['L', 'P'][array_rand(['L', 'P'])];
            $childName = $gender === 'L'
                ? 'I ' . ['Gede', 'Made', 'Nyoman', 'Ketut'][array_rand(['Gede', 'Made', 'Nyoman', 'Ketut'])] . ' ' . ['Arya', 'Putra', 'Bayu', 'Dharma'][array_rand(['Arya', 'Putra', 'Bayu', 'Dharma'])]
                : 'Ni ' . ['Putu', 'Kadek', 'Komang', 'Ketut'][array_rand(['Putu', 'Kadek', 'Komang', 'Ketut'])] . ' ' . ['Ayu', 'Sari', 'Dewi', 'Ratih'][array_rand(['Ayu', 'Sari', 'Dewi', 'Ratih'])];

            Child::create([
                'patient_id' => $mother->id,
                'birth_location' => 'internal',
                'nik' => $this->generateNIK(),
                'no_rm' => 'ANAK-' . str_pad(Child::count() + 1, 4, '0', STR_PAD_LEFT),
                'name' => $childName,
                'gender' => $gender,
                'dob' => now()->subMonths($childAge),
                'birth_weight' => rand(2700, 3800),
                'status' => 'Hidup',
            ]);
        }

        // 4 Anak External (ibu dari luar wilayah)
        for ($i = 0; $i < 4; $i++) {
            $gender = ['L', 'P'][array_rand(['L', 'P'])];
            $childName = $gender === 'L'
                ? 'I ' . ['Wayan', 'Made', 'Nyoman', 'Ketut'][$i] . ' ' . ['Adi', 'Guna', 'Surya', 'Pande'][$i]
                : 'Ni ' . ['Wayan', 'Kadek', 'Komang', 'Ketut'][$i] . ' ' . ['Luh', 'Sri', 'Eka', 'Dwi'][$i];

            $motherNames = ['Ni Wayan Sukerti', 'Ni Made Ariani', 'Ni Ketut Suryani', 'Ni Putu Darmi'];

            Child::create([
                'patient_id' => null,
                'parent_name' => $motherNames[$i],
                'parent_phone' => $this->generatePhone(),
                'parent_address' => 'Br. Luar Wilayah, Desa ' . ['Petang', 'Sangeh', 'Blahkiuh', 'Pelaga'][$i] . ', Kec. ' . ['Petang', 'Abiansemal', 'Abiansemal', 'Petang'][$i],
                'birth_location' => 'external',
                'nik' => $this->generateNIK(),
                'no_rm' => 'ANAK-EXT-' . str_pad($i + 1, 4, '0', STR_PAD_LEFT),
                'name' => $childName,
                'gender' => $gender,
                'dob' => now()->subMonths(rand(3, 36)),
                'birth_weight' => rand(2800, 3500),
                'status' => 'Hidup',
            ]);
        }
    }

    private function createAkseptorKB()
    {
        $this->command->info('   💊 Seeding Akseptor KB (6 pasien)...');

        $kbMethods = KbMethod::all();
        if ($kbMethods->isEmpty()) {
            $this->command->warn('      Skipping KB - No KB Methods found');
            return;
        }

        $methodCategories = ['SUNTIK', 'SUNTIK', 'PIL', 'IUD', 'IMPLANT', 'KONDOM'];

        for ($i = 0; $i < 6; $i++) {
            $method = $kbMethods->where('category', $methodCategories[$i])->first();
            if (!$method) continue;

            $patient = Patient::create([
                'name' => $this->getNextWomanName(),
                'nik' => $this->generateNIK(),
                'no_rm' => 'RM-' . now()->format('Ymd') . '-' . str_pad(Patient::count() + 1, 4, '0', STR_PAD_LEFT),
                'dob' => now()->subYears(rand(22, 40))->subDays(rand(0, 365)),
                'gender' => 'P',
                'address' => $this->getRandomAddress(),
                'phone' => $this->generatePhone(),
                'husband_name' => $this->getNextManName(),
                'category' => 'Umum',
            ]);

            $visitTypes = ['Peserta Baru', 'Peserta Lama', 'Peserta Lama', 'Peserta Lama', 'Ganti Cara', 'Peserta Lama'];

            KbVisit::create([
                'patient_id' => $patient->id,
                'no_rm' => $patient->no_rm,
                'kb_method_id' => $method->id,
                'visit_date' => now()->subDays(rand(1, 60)),
                'next_visit_date' => match($methodCategories[$i]) {
                    'SUNTIK' => now()->addMonths(rand(1, 3)),
                    'PIL' => now()->addMonths(1),
                    'IUD' => now()->addYears(rand(5, 10)),
                    'IMPLANT' => now()->addYears(3),
                    default => now()->addMonths(1),
                },
                'visit_type' => $visitTypes[$i],
                'payment_type' => ['Umum', 'BPJS'][array_rand(['Umum', 'BPJS'])],
                'weight' => rand(45, 70),
                'blood_pressure_systolic' => rand(100, 130),
                'blood_pressure_diastolic' => rand(60, 85),
                'side_effects' => [null, 'Haid tidak teratur', null, 'Nyeri ringan', null, null][$i],
                'midwife_name' => 'Bidan ' . ['Ayu', 'Dewi', 'Sari', 'Wati', 'Ratna', 'Puspa'][$i],
            ]);
        }
    }

    private function createPasienUmum()
    {
        $this->command->info('   🏥 Seeding Pasien Umum (5 pasien)...');

        for ($i = 0; $i < 5; $i++) {
            $isWoman = rand(0, 1) === 1;
            $age = rand(18, 65);

            Patient::create([
                'name' => $isWoman ? $this->getNextWomanName() : $this->getNextManName(),
                'nik' => $this->generateNIK(),
                'no_rm' => 'RM-' . now()->format('Ymd') . '-' . str_pad(Patient::count() + 1, 4, '0', STR_PAD_LEFT),
                'dob' => now()->subYears($age)->subDays(rand(0, 365)),
                'gender' => $isWoman ? 'P' : 'L',
                'address' => $this->getRandomAddress(),
                'phone' => $this->generatePhone(),
                'job' => $isWoman ? $this->getRandomWomanJob() : $this->getRandomManJob(),
                'blood_type' => ['A', 'B', 'AB', 'O'][array_rand(['A', 'B', 'AB', 'O'])],
                'category' => 'Umum',
            ]);
        }
    }
}
