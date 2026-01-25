<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WhoStandardSeeder extends Seeder
{
    public function run(): void
    {
        // Data standar WHO untuk BB/U (Berat Badan per Umur) - Laki-laki 0-12 bulan
        $bbUBoysData = [
            ['age' => 0, 'sd' => [2.1, 2.5, 2.9, 3.3, 3.9, 4.4, 5.0]],
            ['age' => 1, 'sd' => [2.9, 3.4, 3.9, 4.5, 5.1, 5.8, 6.6]],
            ['age' => 2, 'sd' => [3.8, 4.3, 4.9, 5.6, 6.3, 7.1, 8.0]],
            ['age' => 3, 'sd' => [4.4, 5.0, 5.7, 6.4, 7.2, 8.0, 9.0]],
            ['age' => 4, 'sd' => [4.9, 5.6, 6.2, 7.0, 7.8, 8.7, 9.7]],
            ['age' => 5, 'sd' => [5.3, 6.0, 6.7, 7.5, 8.4, 9.3, 10.4]],
            ['age' => 6, 'sd' => [5.7, 6.4, 7.1, 7.9, 8.8, 9.8, 10.9]],
            ['age' => 7, 'sd' => [5.9, 6.7, 7.4, 8.3, 9.2, 10.3, 11.4]],
            ['age' => 8, 'sd' => [6.2, 6.9, 7.7, 8.6, 9.6, 10.7, 11.9]],
            ['age' => 9, 'sd' => [6.4, 7.1, 8.0, 8.9, 9.9, 11.0, 12.3]],
            ['age' => 10, 'sd' => [6.6, 7.4, 8.2, 9.2, 10.2, 11.4, 12.7]],
            ['age' => 11, 'sd' => [6.8, 7.6, 8.4, 9.4, 10.5, 11.7, 13.0]],
            ['age' => 12, 'sd' => [6.9, 7.7, 8.6, 9.6, 10.8, 12.0, 13.3]],
        ];

        // Data standar WHO untuk BB/U - Perempuan 0-12 bulan
        $bbUGirlsData = [
            ['age' => 0, 'sd' => [2.0, 2.4, 2.8, 3.2, 3.7, 4.2, 4.8]],
            ['age' => 1, 'sd' => [2.7, 3.2, 3.6, 4.2, 4.8, 5.5, 6.2]],
            ['age' => 2, 'sd' => [3.4, 3.9, 4.5, 5.1, 5.8, 6.6, 7.5]],
            ['age' => 3, 'sd' => [4.0, 4.5, 5.2, 5.8, 6.6, 7.5, 8.5]],
            ['age' => 4, 'sd' => [4.4, 5.0, 5.7, 6.4, 7.3, 8.2, 9.3]],
            ['age' => 5, 'sd' => [4.8, 5.4, 6.1, 6.9, 7.8, 8.8, 10.0]],
            ['age' => 6, 'sd' => [5.1, 5.7, 6.5, 7.3, 8.2, 9.3, 10.6]],
            ['age' => 7, 'sd' => [5.3, 6.0, 6.8, 7.6, 8.6, 9.8, 11.1]],
            ['age' => 8, 'sd' => [5.6, 6.3, 7.0, 7.9, 9.0, 10.2, 11.6]],
            ['age' => 9, 'sd' => [5.8, 6.5, 7.3, 8.2, 9.3, 10.5, 12.0]],
            ['age' => 10, 'sd' => [5.9, 6.7, 7.5, 8.5, 9.6, 10.9, 12.4]],
            ['age' => 11, 'sd' => [6.1, 6.9, 7.7, 8.7, 9.9, 11.2, 12.8]],
            ['age' => 12, 'sd' => [6.3, 7.0, 7.9, 8.9, 10.1, 11.5, 13.1]],
        ];

        // Data standar WHO untuk TB/U (Tinggi Badan per Umur) - Laki-laki 0-12 bulan
        $tbUBoysData = [
            ['age' => 0, 'sd' => [44.2, 46.1, 48.0, 49.9, 51.8, 53.7, 55.6]],
            ['age' => 1, 'sd' => [48.9, 50.8, 52.8, 54.7, 56.7, 58.6, 60.6]],
            ['age' => 2, 'sd' => [52.4, 54.4, 56.4, 58.4, 60.4, 62.4, 64.4]],
            ['age' => 3, 'sd' => [55.3, 57.3, 59.4, 61.4, 63.5, 65.5, 67.6]],
            ['age' => 4, 'sd' => [57.6, 59.7, 61.8, 63.9, 66.0, 68.0, 70.1]],
            ['age' => 5, 'sd' => [59.6, 61.7, 63.8, 65.9, 68.0, 70.1, 72.2]],
            ['age' => 6, 'sd' => [61.2, 63.3, 65.5, 67.6, 69.8, 71.9, 74.0]],
            ['age' => 7, 'sd' => [62.7, 64.8, 67.0, 69.2, 71.3, 73.5, 75.7]],
            ['age' => 8, 'sd' => [64.0, 66.2, 68.4, 70.6, 72.8, 75.0, 77.2]],
            ['age' => 9, 'sd' => [65.2, 67.5, 69.7, 72.0, 74.2, 76.5, 78.7]],
            ['age' => 10, 'sd' => [66.4, 68.7, 71.0, 73.3, 75.6, 77.9, 80.1]],
            ['age' => 11, 'sd' => [67.6, 69.9, 72.2, 74.5, 76.9, 79.2, 81.5]],
            ['age' => 12, 'sd' => [68.6, 71.0, 73.4, 75.7, 78.1, 80.5, 82.9]],
        ];

        // Data standar WHO untuk TB/U - Perempuan 0-12 bulan
        $tbUGirlsData = [
            ['age' => 0, 'sd' => [43.6, 45.4, 47.3, 49.1, 51.0, 52.9, 54.7]],
            ['age' => 1, 'sd' => [47.8, 49.8, 51.7, 53.7, 55.6, 57.6, 59.5]],
            ['age' => 2, 'sd' => [51.0, 53.0, 55.0, 57.1, 59.1, 61.1, 63.2]],
            ['age' => 3, 'sd' => [53.5, 55.6, 57.7, 59.8, 61.9, 64.0, 66.1]],
            ['age' => 4, 'sd' => [55.6, 57.8, 59.9, 62.1, 64.3, 66.4, 68.6]],
            ['age' => 5, 'sd' => [57.4, 59.6, 61.8, 64.0, 66.2, 68.5, 70.7]],
            ['age' => 6, 'sd' => [58.9, 61.2, 63.5, 65.7, 68.0, 70.3, 72.5]],
            ['age' => 7, 'sd' => [60.3, 62.7, 65.0, 67.3, 69.6, 71.9, 74.2]],
            ['age' => 8, 'sd' => [61.7, 64.0, 66.4, 68.7, 71.1, 73.5, 75.8]],
            ['age' => 9, 'sd' => [62.9, 65.3, 67.7, 70.1, 72.6, 75.0, 77.4]],
            ['age' => 10, 'sd' => [64.1, 66.5, 69.0, 71.5, 73.9, 76.4, 78.9]],
            ['age' => 11, 'sd' => [65.2, 67.7, 70.3, 72.8, 75.3, 77.8, 80.3]],
            ['age' => 12, 'sd' => [66.3, 68.9, 71.4, 74.0, 76.6, 79.2, 81.7]],
        ];

        // Insert BB/U Boys
        foreach ($bbUBoysData as $data) {
            DB::table('who_standards')->insert([
                'gender' => 'L',
                'type' => 'BB_U',
                'age_month' => $data['age'],
                'length_cm' => null,
                'sd_minus_3' => $data['sd'][0],
                'sd_minus_2' => $data['sd'][1],
                'sd_minus_1' => $data['sd'][2],
                'sd_median' => $data['sd'][3],
                'sd_plus_1' => $data['sd'][4],
                'sd_plus_2' => $data['sd'][5],
                'sd_plus_3' => $data['sd'][6],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Insert BB/U Girls
        foreach ($bbUGirlsData as $data) {
            DB::table('who_standards')->insert([
                'gender' => 'P',
                'type' => 'BB_U',
                'age_month' => $data['age'],
                'length_cm' => null,
                'sd_minus_3' => $data['sd'][0],
                'sd_minus_2' => $data['sd'][1],
                'sd_minus_1' => $data['sd'][2],
                'sd_median' => $data['sd'][3],
                'sd_plus_1' => $data['sd'][4],
                'sd_plus_2' => $data['sd'][5],
                'sd_plus_3' => $data['sd'][6],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Insert TB/U Boys
        foreach ($tbUBoysData as $data) {
            DB::table('who_standards')->insert([
                'gender' => 'L',
                'type' => 'TB_U',
                'age_month' => $data['age'],
                'length_cm' => null,
                'sd_minus_3' => $data['sd'][0],
                'sd_minus_2' => $data['sd'][1],
                'sd_minus_1' => $data['sd'][2],
                'sd_median' => $data['sd'][3],
                'sd_plus_1' => $data['sd'][4],
                'sd_plus_2' => $data['sd'][5],
                'sd_plus_3' => $data['sd'][6],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Insert TB/U Girls
        foreach ($tbUGirlsData as $data) {
            DB::table('who_standards')->insert([
                'gender' => 'P',
                'type' => 'TB_U',
                'age_month' => $data['age'],
                'length_cm' => null,
                'sd_minus_3' => $data['sd'][0],
                'sd_minus_2' => $data['sd'][1],
                'sd_minus_1' => $data['sd'][2],
                'sd_median' => $data['sd'][3],
                'sd_plus_1' => $data['sd'][4],
                'sd_plus_2' => $data['sd'][5],
                'sd_plus_3' => $data['sd'][6],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Sample BB/TB (Berat Badan per Tinggi Badan) untuk panjang badan 45-87 cm - Laki-laki
        $bbTbBoysData = [
            ['length' => 45.0, 'sd' => [1.9, 2.0, 2.2, 2.4, 2.6, 2.9, 3.1]],
            ['length' => 50.0, 'sd' => [2.7, 2.9, 3.2, 3.5, 3.8, 4.2, 4.6]],
            ['length' => 55.0, 'sd' => [3.6, 3.9, 4.3, 4.7, 5.2, 5.7, 6.2]],
            ['length' => 60.0, 'sd' => [4.6, 5.1, 5.5, 6.1, 6.7, 7.3, 8.0]],
            ['length' => 65.0, 'sd' => [5.7, 6.3, 6.9, 7.5, 8.2, 9.0, 9.9]],
            ['length' => 70.0, 'sd' => [6.8, 7.4, 8.1, 8.9, 9.7, 10.6, 11.6]],
            ['length' => 75.0, 'sd' => [7.7, 8.5, 9.3, 10.1, 11.1, 12.1, 13.2]],
            ['length' => 80.0, 'sd' => [8.6, 9.4, 10.3, 11.3, 12.3, 13.5, 14.7]],
            ['length' => 85.0, 'sd' => [9.4, 10.3, 11.3, 12.4, 13.5, 14.8, 16.2]],
        ];

        // Sample BB/TB - Perempuan
        $bbTbGirlsData = [
            ['length' => 45.0, 'sd' => [1.8, 2.0, 2.2, 2.4, 2.6, 2.8, 3.1]],
            ['length' => 50.0, 'sd' => [2.6, 2.8, 3.1, 3.4, 3.7, 4.0, 4.4]],
            ['length' => 55.0, 'sd' => [3.4, 3.7, 4.1, 4.5, 4.9, 5.4, 5.9]],
            ['length' => 60.0, 'sd' => [4.4, 4.8, 5.2, 5.7, 6.3, 6.9, 7.5]],
            ['length' => 65.0, 'sd' => [5.4, 5.9, 6.5, 7.1, 7.7, 8.5, 9.3]],
            ['length' => 70.0, 'sd' => [6.4, 7.0, 7.6, 8.3, 9.1, 10.0, 11.0]],
            ['length' => 75.0, 'sd' => [7.3, 8.0, 8.7, 9.5, 10.4, 11.4, 12.5]],
            ['length' => 80.0, 'sd' => [8.1, 8.9, 9.7, 10.6, 11.6, 12.7, 13.9]],
            ['length' => 85.0, 'sd' => [8.9, 9.7, 10.6, 11.6, 12.7, 13.9, 15.2]],
        ];

        // Insert BB/TB Boys
        foreach ($bbTbBoysData as $data) {
            DB::table('who_standards')->insert([
                'gender' => 'L',
                'type' => 'BB_TB',
                'age_month' => null,
                'length_cm' => $data['length'],
                'sd_minus_3' => $data['sd'][0],
                'sd_minus_2' => $data['sd'][1],
                'sd_minus_1' => $data['sd'][2],
                'sd_median' => $data['sd'][3],
                'sd_plus_1' => $data['sd'][4],
                'sd_plus_2' => $data['sd'][5],
                'sd_plus_3' => $data['sd'][6],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Insert BB/TB Girls
        foreach ($bbTbGirlsData as $data) {
            DB::table('who_standards')->insert([
                'gender' => 'P',
                'type' => 'BB_TB',
                'age_month' => null,
                'length_cm' => $data['length'],
                'sd_minus_3' => $data['sd'][0],
                'sd_minus_2' => $data['sd'][1],
                'sd_minus_1' => $data['sd'][2],
                'sd_median' => $data['sd'][3],
                'sd_plus_1' => $data['sd'][4],
                'sd_plus_2' => $data['sd'][5],
                'sd_plus_3' => $data['sd'][6],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $this->command->info('WHO Standards seeded successfully!');
    }
}
