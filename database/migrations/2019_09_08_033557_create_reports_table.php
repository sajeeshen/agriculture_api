<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->nullable()->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->integer('tractor_id')->unsigned();

            $table->foreign('tractor_id')->references('id')->on('tractors')->onDelete('cascade');
            $table->integer('field_id')->unsigned();

            $table->foreign('field_id')->references('id')->on('fields')->onDelete('cascade');
            $table->boolean('approved')->default(false);
            $table->date('report_date');
            $table->float('processed_area');
            $table->integer('approved_user_id')->nullable()->unsigned();
            $table->foreign('approved_user_id')->references('id')->on('users')->onDelete('set null');

            $table->string('report_slug', 100)->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reports');
    }
}
