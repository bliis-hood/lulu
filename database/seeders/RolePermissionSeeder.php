<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'create medicine',
            'view medicine',
            'edit medicine',
            'delete medicine',

            'create prescription',
            'view prescription',
            'edit prescription',
            'delete prescription',

            'create patient',
            'view patient',
            'edit patient',
            'delete patient',

            'create stock',
            'view stock',
            'edit stock',
            'delete stock',

            'assign roles',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Admin
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $admin->syncPermissions(Permission::all());

        // Doctor
        $doctor = Role::firstOrCreate(['name' => 'doctor']);
        $doctor->syncPermissions([
            'create medicine',
            'view medicine',
            'edit medicine',
            'delete medicine',

            'create prescription',
            'view prescription',
            'edit prescription',
            'delete prescription',

            'create patient',
            'view patient',
            'edit patient',
            'delete patient',

            'view stock',
        ]);

        // Pharmacist
        $pharmacist = Role::firstOrCreate(['name' => 'pharmacist']);
        $pharmacist->syncPermissions([
            'create stock',
            'view stock',
            'edit stock',
            'delete stock',

            'view medicine',
            'view prescription',
            'view patient',
        ]);

        // Make user ID 1 an Admin
        $user = User::find(1);

        if ($user) {
            $user->assignRole('admin');
        }
    }
}
