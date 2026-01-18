<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VaccineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Data vaksin sesuai Template Excel Imunisasi dari klien.
     * Updated: Menggunakan istilah HEXAVALEN (bukan DPT-HB-Hib) dan menambahkan PCV, Rota vaksin baru.
     */
    public function run(): void
    {
        $vaccines = [
            // HB0 - Hepatitis B 0 (Diberikan saat lahir)
            [
                'code' => 'HB0',
                'name' => 'HB0',
                'description' => 'Vaksin Hepatitis B dosis pertama, diberikan segera setelah lahir (dalam 24 jam)',
                'min_age_months' => 0,
                'max_age_months' => 1,
                'sort_order' => 1,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // BCG - Bacillus Calmette-Guérin (Tuberkulosis)
            [
                'code' => 'BCG',
                'name' => 'BCG',
                'description' => 'Vaksin untuk mencegah penyakit Tuberkulosis (TB)',
                'min_age_months' => 1,
                'max_age_months' => 3,
                'sort_order' => 2,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Polio Series (1-4)
            [
                'code' => 'POLIO_1',
                'name' => 'POLIO 1',
                'description' => 'Vaksin Polio Oral dosis pertama',
                'min_age_months' => 1,
                'max_age_months' => 4,
                'sort_order' => 3,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'POLIO_2',
                'name' => 'POLIO 2',
                'description' => 'Vaksin Polio Oral dosis kedua',
                'min_age_months' => 2,
                'max_age_months' => 5,
                'sort_order' => 4,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'POLIO_3',
                'name' => 'POLIO 3',
                'description' => 'Vaksin Polio Oral dosis ketiga',
                'min_age_months' => 3,
                'max_age_months' => 6,
                'sort_order' => 5,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'POLIO_4',
                'name' => 'POLIO 4',
                'description' => 'Vaksin Polio Oral dosis keempat',
                'min_age_months' => 4,
                'max_age_months' => 7,
                'sort_order' => 6,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // HEXAVALEN Series (1-3) - Menggantikan DPT-HB-Hib
            [
                'code' => 'HEXAVALEN_1',
                'name' => 'HEXAVALEN 1',
                'description' => 'Vaksin kombinasi 6 antigen (Difteri, Pertusis, Tetanus, Hepatitis B, Haemophilus influenzae type b, dan Polio) dosis pertama',
                'min_age_months' => 2,
                'max_age_months' => 4,
                'sort_order' => 7,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'HEXAVALEN_2',
                'name' => 'HEXAVALEN 2',
                'description' => 'Vaksin kombinasi 6 antigen (Difteri, Pertusis, Tetanus, Hepatitis B, Haemophilus influenzae type b, dan Polio) dosis kedua',
                'min_age_months' => 3,
                'max_age_months' => 5,
                'sort_order' => 8,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'HEXAVALEN_3',
                'name' => 'HEXAVALEN 3',
                'description' => 'Vaksin kombinasi 6 antigen (Difteri, Pertusis, Tetanus, Hepatitis B, Haemophilus influenzae type b, dan Polio) dosis ketiga',
                'min_age_months' => 4,
                'max_age_months' => 6,
                'sort_order' => 9,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // IPV - Inactivated Polio Vaccine (1-2)
            [
                'code' => 'IPV_1',
                'name' => 'IPV 1',
                'description' => 'Vaksin Polio Suntik (Inactivated Polio Vaccine) dosis pertama',
                'min_age_months' => 4,
                'max_age_months' => 6,
                'sort_order' => 10,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'IPV_2',
                'name' => 'IPV 2',
                'description' => 'Vaksin Polio Suntik (Inactivated Polio Vaccine) dosis kedua',
                'min_age_months' => 5,
                'max_age_months' => 7,
                'sort_order' => 11,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // PCV - Pneumococcal Conjugate Vaccine (1-3)
            [
                'code' => 'PCV_1',
                'name' => 'PCV 1',
                'description' => 'Vaksin Pneumokokus (Pneumococcal Conjugate Vaccine) dosis pertama - mencegah pneumonia dan meningitis',
                'min_age_months' => 2,
                'max_age_months' => 4,
                'sort_order' => 12,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'PCV_2',
                'name' => 'PCV 2',
                'description' => 'Vaksin Pneumokokus (Pneumococcal Conjugate Vaccine) dosis kedua - mencegah pneumonia dan meningitis',
                'min_age_months' => 4,
                'max_age_months' => 6,
                'sort_order' => 13,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'PCV_3',
                'name' => 'PCV 3',
                'description' => 'Vaksin Pneumokokus (Pneumococcal Conjugate Vaccine) dosis ketiga - mencegah pneumonia dan meningitis',
                'min_age_months' => 12,
                'max_age_months' => 15,
                'sort_order' => 14,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // MR - Measles Rubella (1-2)
            [
                'code' => 'MR_1',
                'name' => 'MR 1',
                'description' => 'Vaksin Campak dan Rubella dosis pertama',
                'min_age_months' => 9,
                'max_age_months' => 12,
                'sort_order' => 15,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'MR_2',
                'name' => 'MR 2',
                'description' => 'Vaksin Campak dan Rubella dosis kedua (booster)',
                'min_age_months' => 18,
                'max_age_months' => 24,
                'sort_order' => 16,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // ROTA - Rotavirus Vaccine (1-3)
            [
                'code' => 'ROTA_1',
                'name' => 'ROTA 1',
                'description' => 'Vaksin Rotavirus dosis pertama - mencegah diare berat pada bayi',
                'min_age_months' => 2,
                'max_age_months' => 4,
                'sort_order' => 17,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'ROTA_2',
                'name' => 'ROTA 2',
                'description' => 'Vaksin Rotavirus dosis kedua - mencegah diare berat pada bayi',
                'min_age_months' => 4,
                'max_age_months' => 6,
                'sort_order' => 18,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'ROTA_3',
                'name' => 'ROTA 3',
                'description' => 'Vaksin Rotavirus dosis ketiga - mencegah diare berat pada bayi',
                'min_age_months' => 6,
                'max_age_months' => 8,
                'sort_order' => 19,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        // Truncate table first to avoid duplicates
        DB::table('vaccines')->truncate();

        // Insert vaccine data
        DB::table('vaccines')->insert($vaccines);

        $this->command->info('✓ Data ' . count($vaccines) . ' vaksin berhasil diisi ke tabel vaccines');
        $this->command->info('✓ Vaksin yang ditambahkan: HB0, BCG, POLIO (1-4), HEXAVALEN (1-3), IPV (1-2), PCV (1-3), MR (1-2), ROTA (1-3)');
    }
}