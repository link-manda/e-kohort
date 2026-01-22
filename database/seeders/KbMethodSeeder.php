<?php

namespace Database\Seeders;

use App\Models\KbMethod;
use Illuminate\Database\Seeder;

class KbMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $methods = [
            // SUNTIK (Hormonal)
            [
                'code' => 'SUNTIK_1M',
                'name' => 'Suntik 1 Bulan (Cyclofem)',
                'category' => 'SUNTIK',
                'is_hormonal' => true,
            ],
            [
                'code' => 'SUNTIK_3M',
                'name' => 'Suntik 3 Bulan (Depo Progestin)',
                'category' => 'SUNTIK',
                'is_hormonal' => true,
            ],

            // PIL (Hormonal)
            [
                'code' => 'PIL_KOMBINASI',
                'name' => 'Pil Kombinasi',
                'category' => 'PIL',
                'is_hormonal' => true,
            ],
            [
                'code' => 'PIL_LAKTASI',
                'name' => 'Pil Laktasi (Menyusui)',
                'category' => 'PIL',
                'is_hormonal' => true,
            ],

            // IMPLANT (Hormonal)
            [
                'code' => 'IMPLANT_1',
                'name' => 'Implant 1 Batang',
                'category' => 'IMPLANT',
                'is_hormonal' => true,
            ],
            [
                'code' => 'IMPLANT_2',
                'name' => 'Implant 2 Batang',
                'category' => 'IMPLANT',
                'is_hormonal' => true,
            ],

            // IUD (Non-Hormonal - Safe for Hypertension)
            [
                'code' => 'IUD_CUT380A',
                'name' => 'IUD CuT 380A',
                'category' => 'IUD',
                'is_hormonal' => false,
            ],
            [
                'code' => 'IUD_NOVA_T',
                'name' => 'IUD Nova T',
                'category' => 'IUD',
                'is_hormonal' => false,
            ],
            [
                'code' => 'IUD_SILVERLINE',
                'name' => 'IUD Silverline',
                'category' => 'IUD',
                'is_hormonal' => false,
            ],

            // LAINNYA (Non-Hormonal)
            [
                'code' => 'KONDOM',
                'name' => 'Kondom',
                'category' => 'LAINNYA',
                'is_hormonal' => false,
            ],
            [
                'code' => 'MOW',
                'name' => 'MOW (Steril Wanita)',
                'category' => 'LAINNYA',
                'is_hormonal' => false,
            ],
            [
                'code' => 'MOP',
                'name' => 'MOP (Steril Pria)',
                'category' => 'LAINNYA',
                'is_hormonal' => false,
            ],
        ];

        foreach ($methods as $method) {
            KbMethod::updateOrCreate(
                ['code' => $method['code']],
                $method
            );
        }

        $this->command->info('✓ KB methods seeded: ' . count($methods));
    }
}
