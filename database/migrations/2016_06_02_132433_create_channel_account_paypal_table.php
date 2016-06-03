<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChannelAccountPaypalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('channel_account_paypal', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('channel_account_id')->comment('渠道账号id');
            $table->integer('paypal_id')->comment('paypal账号id');
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
        //
        Schema::drop('channel_account_paypal');
    }
}
