<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShsHkZoneTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::create('erp_shs_hk_zone', function (Blueprint $table) {
            $table->increments('id');
            $table->string('partition')->comment('面单分区');
            $table->string('country_cn')->comment('国家中文名');
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
        Schema::drop('erp_shs_hk_zone');
    }
}
