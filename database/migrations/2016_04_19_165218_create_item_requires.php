<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemRequires extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('requires', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order_id')->comment('订单ID');
            $table->integer('item_id')->comment('ITEM ID');
            $table->integer('order_item_id')->comment('订单产品ID');
            $table->string('sku')->comment('SKU');
            $table->integer('quantity')->comment('需求数量');
            $table->enum('is_require', [0, 1])->default(1)->comment('需求状态');
            $table->integer('remark')->comment('备注')->default(null);
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
        Schema::drop('requires');
    }
}
