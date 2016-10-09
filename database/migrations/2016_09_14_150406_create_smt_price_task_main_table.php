<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSmtPriceTaskMainTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::create('smt_price_task_main', function (Blueprint $table) {
            $table->increments('id');
            $table->string('token_id')->comment('渠道帐号id');
            $table->string('shipment_id')->comment('物流id');
            $table->string('shipment_id_op')->coment('粉 电 液 物流选项');
            $table->float('percentage')->comment('百分率');
            $table->float('re_pirce')->comment('限价金额');
            $table->tinyInteger('status')->comment('1 未执行 2已执行');
            $table->string('group')->comment('产品分组');
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
        Schema::drop('smt_price_task_main');
    }
}
