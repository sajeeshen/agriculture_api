<?php

use Illuminate\Database\Seeder;
use App\Tractor;
use App\User;
use Illuminate\Support\Str;
class TractorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $admin_user = User::whereHas('roles', function($q){
            $q->where('name', 'Admin');
        })->first();
        $new_tractor = new Tractor();
        $new_tractor->name = 'Tractor 1';
        $new_tractor->slug = Str::random();
        $new_tractor->save();
        $new_tractor->user()->attach($admin_user);

        $new_tractor = new Tractor();
        $new_tractor->name = 'Tractor 2';
        $new_tractor->slug = Str::random();
        $new_tractor->save();
        $new_tractor->user()->attach($admin_user);

        $new_tractor = new Tractor();
        $new_tractor->name = 'Tractor 3';
        $new_tractor->slug = Str::random();
        $new_tractor->save();
        $new_tractor->user()->attach($admin_user);

        $new_tractor = new Tractor();
        $new_tractor->name = 'Tractor 4';
        $new_tractor->slug = Str::random();
        $new_tractor->save();
        $new_tractor->user()->attach($admin_user);
    }
}
