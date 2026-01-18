<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create Permissions
        $permissions = [
            // User Management
            'manage-users',
            'view-users',
            'create-users',
            'edit-users',
            'delete-users',

            // Patient Management
            'manage-patients',
            'view-all-patients',
            'view-own-patients',
            'create-patients',
            'edit-patients',
            'delete-patients',

            // Pregnancy Management
            'manage-pregnancies',
            'view-all-pregnancies',
            'view-own-pregnancies',
            'create-pregnancies',
            'edit-pregnancies',
            'delete-pregnancies',

            // ANC Visit Management
            'manage-anc-visits',
            'view-all-anc-visits',
            'view-own-anc-visits',
            'create-anc-visits',
            'edit-anc-visits',
            'delete-anc-visits',

            // Immunization Management (new)
            'manage-vaccines',
            'manage-icd10',

            // Reports & Export
            'view-reports',
            'export-data',
            'generate-monthly-reports',

            // Dashboard
            'view-dashboard',
            'view-all-statistics',
            'view-own-statistics',
        ];

        // Create or get permissions (idempotent)
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create Roles and Assign Permissions (idempotent)

        // 1. Admin Role - Full Access
        $adminRole = Role::firstOrCreate(['name' => 'Admin']);
        $adminRole->givePermissionTo(Permission::all());

        // 2. Bidan Koordinator Role - View all, manage reports, manage users
        $koordinatorRole = Role::firstOrCreate(['name' => 'Bidan Koordinator']);
        $koordinatorRole->givePermissionTo([
            'view-users',
            'manage-users',
            'create-users',
            'edit-users',

            'view-all-patients',
            'create-patients',
            'edit-patients',

            'view-all-pregnancies',
            'create-pregnancies',
            'edit-pregnancies',

            'view-all-anc-visits',
            'create-anc-visits',
            'edit-anc-visits',

            'view-reports',
            'export-data',
            'generate-monthly-reports',

            'view-dashboard',
            'view-all-statistics',
        ]);

        // 3. Bidan Desa Role - Only own patients
        $desaRole = Role::firstOrCreate(['name' => 'Bidan Desa']);
        $desaRole->givePermissionTo([
            'view-own-patients',
            'create-patients',
            'edit-patients',

            'view-own-pregnancies',
            'create-pregnancies',
            'edit-pregnancies',

            'view-own-anc-visits',
            'create-anc-visits',
            'edit-anc-visits',

            'view-dashboard',
            'view-own-statistics',
        ]);

        // Assign existing user to Admin role if exists
        $user = User::where('email', 'bidan@example.com')->first();
        if ($user) {
            $user->assignRole('Admin');
            echo "Admin role assigned to: {$user->email}\n";
        }

        echo "Roles and permissions created successfully!\n";
        echo "- Admin (Full Access)\n";
        echo "- Bidan Koordinator (View All, Manage Reports)\n";
        echo "- Bidan Desa (Own Patients Only)\n";
    }
}
