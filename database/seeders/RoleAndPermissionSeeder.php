<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $role = Role::create(['name' => 'author' , 'guard_name' => 'api']);
        $role = Role::create(['name' => 'collaborator' , 'guard_name' => 'api']);

        $permission = Permission::create(['name' => 'create-section' , 'guard_name' => 'api']);
        $permission = Permission::create(['name' => 'edit-section' , 'guard_name' => 'api']);
        
    }
}
