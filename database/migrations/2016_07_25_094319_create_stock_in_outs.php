<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStockInOuts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_in_outs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('quantity')->comment('数量')->default(0);
            $table->float('amount')->comment('总金额')->default(0);
            $table->string('inner_type')->comment('具体出入库类型')->default('0');
            $table->enum('outer_type', ['IN', 'OUT'])->comment('出库还是入库')->default('IN');
            $table->string('relation_id', 64)->comment('出入库来源id')->default('0');
            $table->integer('stock_id')->comment('stock的id号')->default(0);
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
        Schema::drop('stock_in_outs');
    }
}
