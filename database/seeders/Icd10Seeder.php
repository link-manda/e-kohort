<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Icd10Seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Kode ICD-10 khusus untuk imunisasi anak sesuai standar Kemenkes.
     */
    public function run(): void
    {
        $icd10Codes = [
            [
                'code' => 'Z23',
                'name' => 'Need for immunization against single bacterial diseases',
                'description' => 'Kebutuhan imunisasi terhadap penyakit bakteri tunggal (BCG, dll)',
                'category' => 'immunization',
                'keywords' => json_encode(['bcg', 'bakteri', 'tuberkulosis', 'tb']),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'Z24.0',
                'name' => 'Need for immunization against poliomyelitis',
                'description' => 'Kebutuhan imunisasi Polio',
                'category' => 'immunization',
                'keywords' => json_encode(['polio', 'lumpuh layu', 'ipv', 'opv']),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'Z24.6',
                'name' => 'Need for immunization against viral hepatitis',
                'description' => 'Kebutuhan imunisasi Hepatitis B',
                'category' => 'immunization',
                'keywords' => json_encode(['hepatitis', 'hb0', 'hep b', 'hepatitis b']),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'Z27.1',
                'name' => 'Need for immunization against DPT-combined',
                'description' => 'Kebutuhan imunisasi DPT kombinasi (DPT-HB-Hib)',
                'category' => 'immunization',
                'keywords' => json_encode(['dpt', 'difteri', 'pertusis', 'tetanus', 'pentavalen', 'hib']),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'Z27.4',
                'name' => 'Need for immunization against measles',
                'description' => 'Kebutuhan imunisasi Campak/MR',
                'category' => 'immunization',
                'keywords' => json_encode(['campak', 'measles', 'rubella', 'mr', 'mmr']),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'Z00.1',
                'name' => 'Routine child health examination',
                'description' => 'Pemeriksaan kesehatan rutin anak (Bayi Sehat)',
                'category' => 'wellness',
                'keywords' => json_encode(['bayi sehat', 'pemeriksaan rutin', 'wellness', 'tumbuh kembang', 'sehat']),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'Z30.0',
                'name' => 'General counseling and advice on contraception',
                'description' => 'Konseling umum dan nasihat tentang kontrasepsi (KIE)',
                'category' => 'family_planning',
                'keywords' => json_encode(['kie', 'konseling', 'nasihat', 'kb', 'penyuluhan']),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'Z30.1',
                'name' => 'Insertion of (intrauterine) contraceptive device',
                'description' => 'Pemasangan alat kontrasepsi dalam rahim (IUD/AKDR)',
                'category' => 'family_planning',
                'keywords' => json_encode(['iud', 'akdr', 'spiral', 'pasang', 'copper t']),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'Z30.2',
                'name' => 'Sterilization',
                'description' => 'Sterilisasi (MOW/MOP)',
                'category' => 'family_planning',
                'keywords' => json_encode(['steril', 'mow', 'mop', 'tubektomi', 'vasektomi', 'kontap']),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'Z30.4',
                'name' => 'Surveillance of contraceptive drugs',
                'description' => 'Pengawasan obat kontrasepsi (Suntik, Pil, Implan)',
                'category' => 'family_planning',
                'keywords' => json_encode(['suntik', 'pil', 'implan', 'susuk', 'hormonal', '3 bulan', '1 bulan']),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'Z30.5',
                'name' => 'Surveillance of (intrauterine) contraceptive device',
                'description' => 'Pengawasan/Kontrol IUD (Cek posisi, benang, dll)',
                'category' => 'family_planning',
                'keywords' => json_encode(['kontrol iud', 'cek benang', 'posisi', 'akdr', 'aff iud']),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'Z30.9',
                'name' => 'Contraceptive management, unspecified',
                'description' => 'Manajemen kontrasepsi, tidak spesifik',
                'category' => 'family_planning',
                'keywords' => json_encode(['kb', 'kontrasepsi', 'umum']),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        // Truncate table first to avoid duplicates
        DB::table('icd10_codes')->truncate();

        // Insert ICD-10 data
        DB::table('icd10_codes')->insert($icd10Codes);

        $this->command->info('✓ Data ' . count($icd10Codes) . ' kode ICD-10 berhasil diisi ke tabel icd10_codes');
    }
}