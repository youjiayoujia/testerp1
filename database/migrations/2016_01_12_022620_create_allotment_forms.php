<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAllotmentForms extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('allotment_forms', function (Blueprint $table) {
            $table->increments('id');
            $table->string('stock_allotments_id')->comment('调整单号id')->default('0');
            $table->integer('warehouse_positions_id')->comment('库位')->default(0);
            $table->integer('items_id')->comment('item号')->default(0);
            $table->integer('quantity')->comment('数量')->default(0);
            $table->float('amount')->comment('总金额')->default(0.0);
            $table->integer('receive_quantity')->comment('收到数量')->default(0);
            $table->integer('in_warehouse_positions_id')->comment('入库位')->default(0);
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
        Schema::drop('allotment_forms');
    }
}
