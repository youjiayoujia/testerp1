<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePackageAllReports extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('package_all_reports', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('channel_id')->comment('平台')->default(0);
            $table->integer('warehouse_id')->comment('仓库id')->default(0);
            $table->integer('wait_send')->comment('待发货数量')->default(0);
            $table->integer('sending')->comment('发货中数量')->default(0);
            $table->integer('sended')->comment('已发货数量')->default(0);
            $table->integer('more')->comment('3天以上未发货')->default(0);
            $table->integer('less')->comment('3天内未发货')->default(0);
            $table->integer('daily_send')->comment('当天已发货')->default(0);
            $table->integer('need')->comment('缺货')->default(0);
            $table->decimal('daily_sales', 9, 2)->comment('昨日销售额')->default(0);
            $table->decimal('month_sales', 11, 2)->comment('当月销售额')->default(0);
            $table->timestamp('day_time')->comment('日期')->default(NULL);
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
        Schema::drop('package_all_reports');
    }
}
