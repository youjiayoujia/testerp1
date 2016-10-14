<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateErpGzGekouTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::create('erp_gz_gekou', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('geID')->comment('格口号');
            $table->string('geName')->comment('格口名');
            $table->string('country')->comment('国名');
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
        Schema::drop('erp_gz_gekou');
    }
}
