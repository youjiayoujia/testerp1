<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStocks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stocks', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('item_id')->comment('item号')->default(NULL);
            $table->string('sku')->comment('sku')->default(NULL);
            $table->integer('warehouses_id')->comment('仓库id')->default(NULL);
            $table->integer('warehouse_positions_id')->comment('库位id')->default(NULL);
            $table->integer('all_amount')->comment('总数量')->default(NULL);
            $table->integer('available_amount')->comment('可用数量')->default(NULL);
            $table->integer('hold_amount')->comment('hold库存')->default(NULL);
            $table->integer('total_amount')->comment('总金额')->default(NULL);
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
        Schema::drop('stocks');
    }
}
