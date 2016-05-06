<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderBlacklists extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_blacklists', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->comment('收货人姓名');
            $table->string('email')->comment('邮箱');
            $table->string('zipcode')->comment('收货人邮编');
            $table->enum('whitelist', ['0', '1'])->comment('纳入白名单');
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
        Schema::drop('order_blacklists');
    }
}
