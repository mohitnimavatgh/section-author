<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\{User};


class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = array(
            array(
                "data" => [
                    "name" => "Author",
                    "email" => "author@gmail.com",
                    "password" => bcrypt('Author123!@#'),
                    "visible_password" => "Author123!@#",
                ],
                "role" => "author"
            ),
            array(
                "data" => [
                    "name" => "Collaborator",
                    "email" => "collaborator@gmail.com",
                    "password" => bcrypt('Collaborator123!@#'),
                    "visible_password" => "Collaborator123!@#",
                ],
                "role" => "collaborator"
            )
        );

        foreach($users as $user) {
            $user_create = User::create($user["data"]);
            $role = Role::where('name' , $user['role'])->first();
            $user_create->assignRole($role);
            $permissions = Permission::all();
            $user_create->givePermissionTo($permissions);
        } 
    }
}
