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
            $table->string('image')->comment('图片');
            $table->integer('quantity')->comment('数量');
            $table->float('price')->comment('金额');
            $table->integer('is_active')->comment('是否有效');
            $table->string('status')->comment('发货状态');
            $table->integer('is_gift')->comment('是否赠品');
            $table->string('remark')->comment('备注')->nullable()->default(NULL);
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
