<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Role;
use App\User;
class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $role_user = Role::where('name', 'User')->first();
        $role_supervisor = Role::where('name', 'Supervisor')->first();
        $role_admin = Role::where('name', 'Admin')->first();
        $user = new User();
        $user->first_name = 'Sajeesh';
        $user->last_name = 'Namboothiri';
        $user->email = 'normal@admin.com';
        $user->slug = Str::random();
        $user->password = app('hash')->make('password');
        $user->save();
        $user->roles()->attach($role_user);

        $admin = new User();
        $admin->first_name = 'Admin';
        $admin->last_name = 'Admin';
        $admin->email = 'admin@admin.com';
        $admin->slug = Str::random();
        $admin->password = app('hash')->make('password');
        $admin->save();
        $admin->roles()->attach($role_admin);

        $supervisor = new User();
        $supervisor->first_name = 'Supervisor';
        $supervisor->last_name = 'One';
        $supervisor->slug = Str::random();
        $supervisor->email = 'supervisor@admin.com';
        $supervisor->password = app('hash')->make('password');
        $supervisor->save();
        $supervisor->roles()->attach($role_supervisor);
    }
}
