<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSmtPriceTaskTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
     Schema::create('smt_price_task', function (Blueprint $table) {
            $table->increments('id');
            $table->string('productID')->comment('要调控价格的产品ID');
            $table->string('account')->comment('产品ID的所属账号');
            $table->tinyInteger('status')->coment('1 未执行 2已执行 3执行异常');
            $table->string('shipment_id')->comment('物流选项');
            $table->integer('percentage')->comment('百分率');
            $table->float('re_pirce')->comment('限价金额');
            $table->integer('main_id')->comment('task_main id');
            $table->timestamp('api_time')->comment('请求API的时间');
            $table->text('remark')->comment('调价失败的备注');
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
        Schema::drop('smt_price_task');
    }
}
