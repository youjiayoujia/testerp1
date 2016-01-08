<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStockAdjustment extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_adjustments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('adjust_forms_id')->comment('调整单号id')->default(NULL);
            $table->integer('item_id')->comment('item号')->default(NULL);
            $table->string('sku')->comment('sku')->default(NULL);
            $table->enum('type', ['入库','出库'])->comment('出入库类型')->default(NULL);
            $table->integer('warehouse_positions_id')->comment('库位')->default(NULL);
            $table->integer('amount')->comment('调整数量')->default(NULL);
            $table->float('total_amount')->comment('调整金额')->default(NULL);
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
        Schema::drop('stock_adjustments');
    }
}
