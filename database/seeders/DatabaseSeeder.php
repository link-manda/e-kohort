<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed roles and permissions first
        $this->call([
            RolePermissionSeeder::class,
            Icd10Seeder::class,
            KbMethodSeeder::class,
            VaccineSeeder::class,
            WhoStandardSeeder::class,
            UserSeeder::class,

            // Comprehensive dummy data (50 patients with all visits & relationships)
            ComprehensiveDataSeeder::class,
        ]);

        $this->command->info('🎉 All seeders completed successfully!');
    }
}