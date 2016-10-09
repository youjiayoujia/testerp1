<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEbayAccountSetTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ebay_account_set', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('account_id')->comment('渠道账号id')->default(0);
            $table->string('big_paypal')->comment('大PP')->default('');
            $table->string('small_paypal')->comment('小PP')->default('');
            $table->text('currency')->comment('币种临界值')->default('');
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
        Schema::drop('ebay_account_set');
    }
}
