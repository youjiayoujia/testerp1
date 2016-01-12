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
            $table->string('stock_allotments_id')->comment('调整单号id')->default(NULL);
            $table->integer('warehouse_positions_id')->comment('库位')->default(NULL);
            $table->integer('item_id')->comment('item号')->default(NULL);
            $table->string('sku')->comment('sku')->default(NULL);
            $table->integer('amount')->comment('数量')->default(NULL);
            $table->float('total_amount')->comment('总金额')->default(NULL);
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
