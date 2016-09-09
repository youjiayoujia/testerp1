<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePickReports extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pick_reports', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->comment('拣货人id')->default(0);
            $table->integer('warehouse_id')->comment('仓库id')->default(0);
            $table->integer('single')->comment('单单')->default(0);
            $table->integer('singleMulti')->comment('单多')->default(0);
            $table->integer('multi')->comment('多多')->default(0);
            $table->integer('missing_pick')->comment('漏检数')->default(0);
            $table->integer('today_pick')->comment('今日拣货数')->default(0);
            $table->integer('today_picklist')->comment('今日分配拣货单数')->default(0);
            $table->timestamp('day_time')->comment('时间')->default(0);
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
        Schema::drop('pick_reports');
    }
}
