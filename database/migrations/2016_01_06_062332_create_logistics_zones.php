<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLogisticsZones extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('logistics_zones', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->comment('物流分区');
            $table->integer('logistics_id')->comment('物流方式');
            $table->string('countries')->comment('国家');
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
        Schema::drop('logistics_zones');
    }
}
