<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

use App\User;
use App\Field;
class FieldsTableSeeder extends Seeder
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
        $fields = new Field();
        $fields->name = 'Some Field';
        $fields->area = 5000.50;
        $fields->active = true;
        $fields->crops_type = "Wheat";
        $fields->slug = 'some_field_'.Str::random();
        $fields->save();
        $fields->user()->attach($admin_user);

        $fields = new Field();
        $fields->name = 'Some Field 1';
        $fields->area = 80000.95;
        $fields->active = true;
        $fields->crops_type = "Wheat";
        $fields->slug = 'some_field1_'.Str::random();
        $fields->save();
        $fields->user()->attach($admin_user);
    }
}
