<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStockTakings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_takings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('taking_id')->comment('库存盘点id')->default('0');
            $table->integer('stock_taking_by')->comment('盘点人')->default(0);
            $table->timestamp('stock_taking_time')->comment('盘点时间')->default('0000-00-00 00:00:00');
            $table->enum('create_taking_adjustment',['1', '0'])->comment('是否可以生成调整单')->default('0');
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
        Schema::drop('stock_takings');
    }
}
