<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLogisticsChannel extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('logistics_channel', function (Blueprint $table) {
            $table->increments('id')->comment('ID');
            $table->integer('logistics_id')->comment('物流方式ID');
            $table->integer('channel_id')->comment('渠道ID');
            $table->string('url')->comment('物流追踪网址')->nullable()->default(NULL);
            $table->enum('is_up', ['0', '1'])->comment('是否上传')->nullable()->default('0');
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
        Schema::drop('logistics_channel');
    }
}
