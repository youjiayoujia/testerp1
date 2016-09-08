<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchaseStaticstics extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_staticstics', function (Blueprint $table) {
            $table->increments('id');
            $table->string('purchase_adminer')->comment('采购负责人');
            $table->integer('sku_num')->comment('管理的SKU数');
            $table->string('need_purchase_num')->coment('必须当天内下单SKU数');
            $table->integer('fifteenday_need_order_num')->comment('15天缺货订单');
            $table->string('fifteenday_total_order_num')->comment('15天内所有订单');
            $table->string('need_percent')->comment('订单缺货率');
            $table->string('need_total_num')->comment('缺货总数');
            $table->string('avg_need_day')->comment('平均缺货天数');
            $table->string('long_need_day')->comment('最长缺货天数');
            $table->string('purchase_order_exceed_time')->comment('采购单超期');
            $table->string('month_order_num')->comment('当月累计下单数量');
            $table->string('month_order_money')->comment('当月累计下单总金额');
            $table->string('total_carriage')->comment('累计运费');
            $table->string('save_money')->comment('节约成本');
            $table->string('get_time')->comment('获取时间');
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
        Schema::drop('purchase_staticstics');
    }
}
