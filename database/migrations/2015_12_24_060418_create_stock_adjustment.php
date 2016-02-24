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
            $table->string('adjust_form_id')->comment('调整单号id')->default(0);
            $table->integer('warehouses_id')->comment('仓库id')->default(0);
            $table->integer('adjust_by')->comment('调整人')->default(0);
            $table->text('remark')->comment('备注')->default(NULL);
            $table->integer('check_by')->comment('审核人')->default(0);
            $table->date('check_time')->comment('审核时间')->default('0000-00-00');
            $table->enum('status', ['N', 'Y'])->comment("审核状态")->default('N');
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
