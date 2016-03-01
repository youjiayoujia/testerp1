<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdjustForms extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('adjust_forms', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('stock_adjustment_id')->comment('调整单号id')->default(0);
            $table->integer('item_id')->comment('item号')->default(0);
            $table->enum('type', ['IN','OUT'])->comment('出入库类型')->default('IN');
            $table->integer('warehouse_position_id')->comment('库位')->default('0');
            $table->integer('quantity')->comment('调整数量')->default(0);
            $table->float('amount')->comment('调整金额')->default(0.0);
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
        Schema::drop('adjust_forms');
    }
}
