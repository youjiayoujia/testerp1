<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStockOuts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_outs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('item_id')->comment('item号')->default(NULL);
            $table->string('sku')->comment('sku')->default(NULL);
            $table->integer('amount')->comment('数量')->default(NULL);
            $table->integer('total_amount')->comment('总金额')->default(NULL);
            $table->text('remark')->comment('备注')->default(NULL);
            $table->integer('warehouses_id')->comment('仓库id')->default(NULL);
            $table->integer('warehouse_positions_id')->comment('库位id')->default(NULL);
            $table->string('type')->comment('出库类型')->default(NULL);
            $table->string('relation_id', 64)->comment('出库类型id')->default(NULL);
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
        Schema::drop('stock_outs');
    }
}
