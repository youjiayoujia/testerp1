<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChannelAccountOperator extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('channel_account_operators', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('channel_account_id')->comment('渠道账号');
            $table->integer('user_id')->comment('运营人员');
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
        Schema::drop('channel_account_operators');
    }
}
