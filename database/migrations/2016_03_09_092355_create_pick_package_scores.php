<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePickPackageScores extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pick_package_scores', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('picklist_id')->comment('pick号')->default(0);
            $table->integer('package_id')->comment('package号')->default(0);
            $table->integer('package_score')->comment('package得分')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('pick_package_scores');
    }
}
