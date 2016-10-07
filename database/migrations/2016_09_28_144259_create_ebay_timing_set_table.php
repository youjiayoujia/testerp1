<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEbayTimingSetTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ebay_timing_set', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->default('')->comment('规则名称');
            $table->integer('account_id')->comment('账号')->default(0);
            $table->integer('site')->comment('站点')->default(0);
            $table->integer('warehouse')->comment('仓库')->default(1);
            $table->string('start_time')->default('')->comment('起始时间');
            $table->string('end_time')->default('')->comment('结束时间');
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
        Schema::drop('ebay_timing_set');
    }
}
