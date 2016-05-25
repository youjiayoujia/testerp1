<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order_id')->comment('订单ID');
            $table->integer('item_id')->comment('货品ID');
            $table->string('sku')->comment('sku');
            $table->string('channel_sku')->comment('渠道sku');
            $table->integer('quantity')->comment('数量');
            $table->float('price')->comment('金额');
            $table->string('currency')->comment('币种');
            $table->enum('is_active', [0, 1])->comment('是否有效')->nullable()->default(1);
            $table->enum('status', ['NEW', 'PACKED', 'SHIPPED'])->comment('发货状态')->nullable()->default('NEW');
            $table->enum('is_gift', [0, 1])->comment('是否赠品')->nullable()->default(0);
            $table->string('remark')->comment('备注')->nullable()->default(NULL);
            $table->string('orders_item_number')->comment('产品的广告ID')->nullable()->default(NULL);
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
        Schema::drop('order_items');
    }
}
