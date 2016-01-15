<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStockAllotments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_allotments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('allotment_id')->comment('调拨单号')->default(NULL);
            $table->integer('out_warehouses_id')->comment('调出仓库')->default(0);
            $table->integer('in_warehouses_id')->comment('调入仓库')->default(0);
            $table->text('remark')->comment('备注')->default(NULL);
            $table->integer('allotment_man_id')->comment('调拨人')->default(0);
            $table->date('allotment_time')->comment('调拨时间')->default(NULL);
            $table->enum('allotment_status',['new', 'pick', 'out', 'check', 'over'])->default('new');
            $table->integer('check_man_id')->comment('审核人')->default(0);
            $table->enum('check_status', ['N', 'Y'])->comment('审核状态')->default('N');
            $table->date('check_time')->comment('审核时间')->default(NULL);
            $table->integer('checkform_man_id')->comment('对单人')->default(0);
            $table->date('checkform_time')->comment('对单时间')->default(NULL);
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
        Schema::drop('stock_allotments');
    }
}
