<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLogisticsBelongsTos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('logistics_belongstos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('logistics_id')->comment('物流id')->default(0);
            $table->integer('logistics_channel_id')->comment('渠道物流名id')->default(0);
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
        Schema::drop('logistics_belongstos');
    }
}
