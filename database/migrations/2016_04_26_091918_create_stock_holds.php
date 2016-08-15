<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStockHolds extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_holds', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('quantity')->comment('数量')->default(0);
            $table->string('type')->comment('类型')->default(NULL);
            $table->integer('relation_id')->comment('相关的id')->default(0);
            $table->integer('stock_id')->comment('stock_id')->default(0);
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
        Schema::drop('stock_holds');
    }
}
