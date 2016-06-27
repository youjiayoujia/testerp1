<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLogisticsChannelNames extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('logistics_channel_names', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('logistics_id')->comment('物流id')->default(0);
            $table->integer('channel_id')->comment('渠道id')->default(0);
            $table->string('name')->comment('渠道对应的物流名')->default(NULL);
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
        Schema::drop('channel_logistics_names');
    }
}
