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
            $table->integer('purchase_adminer')->comment('采购负责人');
            $table->integer('sku_num')->comment('管理的SKU数');
            $table->integer('need_purchase_num')->coment('必须当天内下单SKU数');
            $table->integer('fifteenday_need_order_num')->comment('15天缺货订单');
            $table->integer('fifteenday_total_order_num')->comment('15天内所有订单');
            $table->decimal('need_percent',2,4)->comment('订单缺货率');
            $table->integer('need_total_num')->comment('缺货总数');
            $table->decimal('avg_need_day',5,2)->comment('平均缺货天数');
            $table->decimal('long_need_day',2,1)->comment('最长缺货天数');
            $table->integer('purchase_order_exceed_time')->comment('采购单超期');
            $table->integer('month_order_num')->comment('当月累计下单数量');
            $table->integer('month_order_money')->comment('当月累计下单总金额');
            $table->decimal('total_carriage',11,2)->comment('累计运费');
            $table->decimal('save_money',11,2)->comment('节约成本');
            $table->timestamp('get_time')->comment('获取时间');
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
