<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderPaypalDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_paypal_detail', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order_id')->comment('订单id');
            $table->integer('paypal_id')->coment('paypalId');
            $table->string('paypal_account')->comment('买家 paypal账户');
            $table->string('paypal_buyer_name')->comment('收货人');
            $table->string('paypal_address')->comment('买家 paypal地址');
            $table->string('paypal_country')->comment('买家国家');//paypal_country
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
        Schema::drop('order_paypal_detail');
    }
}
