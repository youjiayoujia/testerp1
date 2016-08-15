<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLogisticsZoneCountry extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('logistics_zone_countries', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('logistics_zone_id')->comment("分区报价id")->default(0);
            $table->integer('country_id')->comment('国家id')->default(0);
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
        Schema::drop('logistics_zone_countries');
    }
}
