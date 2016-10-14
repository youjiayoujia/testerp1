<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateErpShunyouAreaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::create('erp_shunyou_area', function (Blueprint $table) {
            $table->increments('id');
            $table->string('country_cn')->comment('国家名');
            $table->string('country_code')->comment('国家简称');
            $table->tinyInteger('area_code')->comment('区域代码');
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
        Schema::drop('erp_shunyou_area');
    }
}

