<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePackageItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('package_items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('item_id')->comment('货品ID');
            $table->integer('package_id')->comment('包裹ID');
            $table->integer('order_item_id')->comment('订单产品ID');
            $table->integer('quantity')->comment('数量');
            $table->text('remark')->comment('备注');
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
        Schema::drop('package_items');
    }
}
