<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaypalRates extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('paypal_rates', function (Blueprint $table) {
            $table->increments('id');
            $table->float('transactions_fee_big')->commit('大pp成交费用');
            $table->float('transactions_fee_small')->commit('小pp成交费用');
            $table->float('fixed_fee_big')->commit('大PP固定费用');
            $table->float('fixed_fee_small')->commit('小PP固定费用');
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
        Schema::drop('paypal_rates');
    }
}
