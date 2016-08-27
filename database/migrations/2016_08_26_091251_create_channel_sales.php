<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChannelSales extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('channel_sales', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('item_id')->comment('item号')->default(0);
            $table->string('channel_sku')->comment('卖家sku')->default(NULL);
            $table->integer('quantity')->comment('数量')->default(0);
            $table->integer('account_id')->comment('渠道帐号id')->default(0);
            $table->timestamp('create_time')->comment('订单销量时间')->default(NULL);
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
        Schema::drop('channel_sales');
    }
}
