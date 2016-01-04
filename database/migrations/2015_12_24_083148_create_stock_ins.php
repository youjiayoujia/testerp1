<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStockIns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_ins', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('item_id')->comment('item表id')->default(NULL);
            $table->string('sku')->comment('sku')->default(NULL);
            $table->integer('amount')->comment('数量')->default(NULL);
            $table->float('total_amount')->comment('总金额')->default(NULL);
            $table->integer('warehouses_id')->comment('仓库id')->default(NULL);
            $table->integer('warehouse_positions_id')->comment('库位id')->default(NULL);
            $table->string('type')->comment('入库类型')->default(NULL);
            $table->string('relation_id', 64)->comment('入库来源id')->default(NULL);
            $table->text('remark')->comment('备注')->default(NULL);
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
        Schema::drop('stock_ins');
    }
}
