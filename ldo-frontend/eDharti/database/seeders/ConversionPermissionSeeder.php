<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class ConversionPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();


        $permission = Permission::firstOrCreate(['name' => 'calculate.conversion']);

        // Assign the permission to the 'super-admin' role
        $role = Role::where('name', 'super-admin')->first();
        if ($role && !$role->hasPermissionTo($permission)) {
            $role->givePermissionTo($permission);
        }

        // Assign the permission to the 'applicant' role
        $role = Role::where('name', 'applicant')->first();
        if ($role && !$role->hasPermissionTo($permission)) {
            $role->givePermissionTo($permission);
        }
    }
}
