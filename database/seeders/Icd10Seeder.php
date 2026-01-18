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
        ];

        // Truncate table first to avoid duplicates
        DB::table('icd10_codes')->truncate();

        // Insert ICD-10 data
        DB::table('icd10_codes')->insert($icd10Codes);

        $this->command->info('✓ Data ' . count($icd10Codes) . ' kode ICD-10 berhasil diisi ke tabel icd10_codes');
    }
}
