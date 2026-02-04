<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as FakerFactory;
use Carbon\Carbon;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = FakerFactory::create();

        $defaults = [
            ['name' => 'Admin', 'email' => 'yanti@e-kohort.com', 'password' => 'password', 'roles' => ['Admin']],
            ['name' => 'Bidan Koordinator', 'email' => 'nia@e-kohort.com', 'password' => 'password', 'roles' => ['Bidan Koordinator']],
            ['name' => 'Bidan Desa', 'email' => 'diah@e-kohort.com', 'password' => 'password', 'roles' => ['Bidan Desa']],
        ];

        foreach ($defaults as $d) {
            $user = User::updateOrCreate(
                ['email' => $d['email']],
                [
                    'name' => $d['name'],
                    'email_verified_at' => Carbon::now(),
                    'password' => Hash::make($d['password']),
                ]
            );

            // Assign roles if Spatie is installed and role exists
            if (class_exists('\Spatie\Permission\Models\Role')) {
                try {
                    foreach ($d['roles'] as $roleName) {
                        if (\Spatie\Permission\Models\Role::where('name', $roleName)->exists()) {
                            $user->assignRole($roleName);
                        }
                    }
                } catch (\Throwable $e) {
                    // ignore role assignment errors
                }
            }
        }

        // Create a few additional random users
        for ($i = 0; $i < 10; $i++) {
            $email = $faker->unique()->safeEmail;
            $name = $faker->name;

            User::updateOrCreate(
                ['email' => $email],
                [
                    'name' => $name,
                    'email_verified_at' => Carbon::now()->subDays(rand(0, 30)),
                    'password' => Hash::make('password'),
                ]
            );
        }
    }
}