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
            $table->integer('adjustment_by')->comment('调整人')->default(0);
            $table->timestamp('adjustment_time')->comment('调整时间')->default('0000-00-00 00:00:00');
            $table->integer('check_by')->comment('审核人')->default(0);
            $table->integer('create_status')->comment('调整单是否生成')->default('0');
            $table->enum('check_status', ['0', '1', '2'])->comment('审核状态')->default('0');
            $table->timestamp('check_time')->comment('审核时间')->default('0000-00-00 00:00:00');
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
