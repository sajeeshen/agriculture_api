<?php

use Illuminate\Database\Seeder;
use App\Role;
class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $role_user = new Role();
        $role_user->name = 'User';
        $role_user->description = 'A normal User';
        $role_user->save();
        $role_author = new Role();
        $role_author->name = 'Supervisor';
        $role_author->description = 'Supervisor Users';
        $role_author->save();
        $role_admin = new Role();
        $role_admin->name = 'Admin';
        $role_admin->description = 'Super Admin';
        $role_admin->save();
    }
}
