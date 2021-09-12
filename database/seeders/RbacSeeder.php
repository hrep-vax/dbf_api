<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RbacSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Regular user permissions
        Permission::create(['name' => 'create.test_resource']);
        Permission::create(['name' => 'read.test_resource']);
        Permission::create(['name' => 'update.test_resource']);
        Permission::create(['name' => 'delete.test_resource']);
        Permission::create(['name' => 'fetch.test_resources']);

        // Create regular user role and assign created permissions
        Role::create(['name' => 'regular'])
            ->givePermissionTo([
                'create.test_resource', 'update.test_resource', 'read.test_resource',
                'delete.test_resource', 'fetch.test_resources'
            ]);

        // Admin Permissions
        Permission::create(['name' => 'create.user']);
        Permission::create(['name' => 'read.user']);
        Permission::create(['name' => 'update.user']);
        Permission::create(['name' => 'delete.user']);
        Permission::create(['name' => 'fetch.users']);

        // Create admin user role and assign created permissions
        $role = Role::create(['name' => 'admin']);
        $role->givePermissionTo(Permission::all());
    }
}
