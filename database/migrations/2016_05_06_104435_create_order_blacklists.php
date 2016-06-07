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
            $table->integer('channel_id')->comment('平台');
            $table->string('ordernum')->comment('订单号');
            $table->string('name')->comment('收货人姓名');
            $table->string('email')->comment('邮箱');
            $table->string('zipcode')->comment('收货人邮编');
            $table->enum('type',
                [
                    'CONFIRMED', 'SUSPECTED', 'WHITE'
                ])->comment('类型')->default('SUSPECTED');
            $table->text('remark')->comment('备注')->nullable()->default(NULL);
            $table->integer('total_order')->comment('订单总数');
            $table->integer('refund_order')->comment('退款订单数');
            $table->string('refund_rate')->comment('退款率');
            $table->enum('color', ['orange', 'blue', 'green', 'white'])->comment('颜色分类')->default(NULL);
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
