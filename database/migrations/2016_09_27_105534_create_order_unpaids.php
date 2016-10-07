<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderUnpaids extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_unpaids', function (Blueprint $table) {
            $table->increments('id');
            $table->string('ordernum')->comment('订单号');
            $table->string('remark')->comment('要求');
            $table->string('note')->comment('备注')->default(NULL);
            $table->date('date')->comment('日期');
            $table->integer('channel_id')->comment('销售账号');
            $table->integer('customer_id')->comment('客服');
            $table->enum('status', [
                    'PERFORM',
                    'NOT_PERFORM',
                    'CONFIRM',])->default('CONFIRM')->comment('状态');
            $table->timestamps();
            $table->softdeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('order_unpaids');
    }
}
