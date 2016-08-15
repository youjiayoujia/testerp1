<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEbaySellerCodeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ebay_seller_code', function (Blueprint $table) {
            $table->increments('id');
            $table->string('seller_code')->comment('自定义销售代码');
            $table->integer('user_id')->comment('user id');
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
        Schema::drop('ebay_seller_code');
    }
}
