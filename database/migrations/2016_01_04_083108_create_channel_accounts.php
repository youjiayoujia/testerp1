<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChannelAccounts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('channel_accounts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('channel_id')->comment('渠道');
            $table->string('account')->comment('账号');
            $table->string('country')->comment('账号国家');
            $table->string('currency')->comment('账号币种');
            $table->string('prefix')->comment('账号前缀');
            $table->string('title')->comment('账号名称');
            $table->text('brief')->comment('账号简介');
            $table->text('token')->comment('账号接口');
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
        Schema::drop('channel_accounts');
    }
}
