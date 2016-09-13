<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePackReports extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pack_reports', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->comment('操作人员')->default(0);
            $table->integer('warehouse_id')->comment('仓库id')->default(0);
            $table->integer('yesterday_send')->comment('昨天发货数')->default(0);
            $table->integer('single')->comment('单单')->default(0);
            $table->integer('singleMulti')->comment('单多')->default(0);
            $table->integer('multi')->comment('多多')->default(0);
            $table->integer('all_worktime')->comment('总工时')->default(0);
            $table->integer('error_send')->comment('发错货数')->default(0);
            $table->timestamp('day_time')->comment('时间')->default(NULL);
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
        Schema::drop('pack_reports');
    }
}
