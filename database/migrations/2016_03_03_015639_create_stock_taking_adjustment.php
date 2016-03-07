<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStockTakingAdjustment extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_taking_adjustments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('stock_taking_id')->comment('库存盘点id')->default(0);
            $table->integer('adjustment_by')->comment('调整人')->default(0);
            $table->timestamp('adjustment_time')->comment('调整时间')->default('0000-00-00 00:00:00');
            $table->integer('check_by')->comment('审核人')->default(0);
            $table->enum('check_status', ['0', '1'])->comment('审核状态')->default('0');
            $table->timestamp('check_time')->comment('审核时间')->default('0000-00-00 00:00:00');
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
        Schema::drop('stock_taking_adjustments');
    }
}
