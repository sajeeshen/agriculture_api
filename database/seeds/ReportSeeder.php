<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Report;
use App\Tractor;
use App\Field;
use Illuminate\Support\Str;
use Carbon\Carbon;
class ReportSeeder extends Seeder
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

        $report = new Report();
        $report->tractor()->associate(Tractor::find(1));
        $report->user()->associate($admin_user);
        $report->field()->associate(Field::find(1));
        $report->approved_user()->associate($admin_user);
        $report->report_date = date('Y-m-d');
        $report->processed_area = 400;
        $report->report_slug = Str::random();
        $report->save();
        //$report->user()->attach($admin_user);
    }
}
