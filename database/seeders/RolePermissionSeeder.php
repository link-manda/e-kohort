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

            // Immunization Management
            'manage-vaccines',
            'manage-icd10',

            // KB Management
            'manage-kb',
            'view-kb',
            'create-kb',
            'edit-kb',

            // General Visit (Poli Umum) Management
            'view-general-visits',
            'create-general-visits',
            'edit-general-visits',

            // Growth / Poli Gizi Management
            'view-growth',
            'create-growth',

            // Reports & Export
            'view-reports',
            'export-data',
            'generate-monthly-reports',

            // Role & Permission Management
            'manage-roles',

            // Dashboard
            'view-dashboard',
            'view-all-statistics',
            'view-own-statistics',
        ];

        // Create or get permissions (idempotent)
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // =========================================================
        // 1. Admin Role - Full Access
        // =========================================================
        $adminRole = Role::firstOrCreate(['name' => 'Admin']);
        $adminRole->syncPermissions(Permission::all());

        // =========================================================
        // 2. Bidan Koordinator - View all, manage reports, manage users
        //    Can access: all clinical menus, export, reports, user mgmt
        //    Cannot: manage vaccines/ICD10/roles (master data admin only)
        // =========================================================
        $koordinatorRole = Role::firstOrCreate(['name' => 'Bidan Koordinator']);
        $koordinatorRole->syncPermissions([
            // User management
            'view-users',
            'manage-users',
            'create-users',
            'edit-users',

            // Patient
            'view-all-patients',
            'create-patients',
            'edit-patients',

            // Pregnancy & ANC
            'view-all-pregnancies',
            'create-pregnancies',
            'edit-pregnancies',
            'view-all-anc-visits',
            'create-anc-visits',
            'edit-anc-visits',

            // KB
            'manage-kb',
            'view-kb',
            'create-kb',
            'edit-kb',

            // Poli Umum
            'view-general-visits',
            'create-general-visits',
            'edit-general-visits',

            // Poli Gizi
            'view-growth',
            'create-growth',

            // Reports & Export
            'view-reports',
            'export-data',
            'generate-monthly-reports',

            // Dashboard
            'view-dashboard',
            'view-all-statistics',
        ]);

        // =========================================================
        // 3. Bidan Desa - Own patients only
        //    Can access: all clinical menus (own data), no export/reports/admin
        // =========================================================
        $desaRole = Role::firstOrCreate(['name' => 'Bidan Desa']);
        $desaRole->syncPermissions([
            // Patient (own only)
            'view-own-patients',
            'create-patients',
            'edit-patients',

            // Pregnancy & ANC (own only)
            'view-own-pregnancies',
            'create-pregnancies',
            'edit-pregnancies',
            'view-own-anc-visits',
            'create-anc-visits',
            'edit-anc-visits',

            // KB (own patients)
            'view-kb',
            'create-kb',
            'edit-kb',

            // Poli Umum (own patients)
            'view-general-visits',
            'create-general-visits',

            // Poli Gizi (own patients)
            'view-growth',
            'create-growth',

            // Dashboard
            'view-dashboard',
            'view-own-statistics',
        ]);

        // Assign existing user to Admin role if exists
        $user = User::where('email', 'bidan@example.com')->first();
        if ($user) {
            $user->assignRole('Admin');
            echo "Admin role assigned to: {$user->email}\n";
        }

        echo "Roles and permissions created/updated successfully!\n";
        echo "- Admin (Full Access)\n";
        echo "- Bidan Koordinator (View All, Manage Reports, All Clinical Menus)\n";
        echo "- Bidan Desa (Own Patients Only, All Clinical Menus)\n";
    }
}
