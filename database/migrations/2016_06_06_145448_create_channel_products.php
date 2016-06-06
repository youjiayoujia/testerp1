<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChannelProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('channel_products', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('channel_account_id')->comment('渠道帐号');
            $table->integer('item_id')->comment('item');
            $table->string('channel_sku')->comment('渠道sku');
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
        Schema::drop('channel_products');
    }
}
