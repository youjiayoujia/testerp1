<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLogisticsRules extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('logistics_rules', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('priority')->comment('优先级');
            $table->string('country')->comment('国家');
            $table->double('weight_from', 15, 2)->comment('重量从');
            $table->double('weight_to', 15, 2)->comment('重量至');
            $table->double('order_amount', 15, 2)->comment('订单金额');
            $table->enum('is_clearance', ['0', '1'])->comment('是否通关');
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
        Schema::drop('logistics_rules');
    }
}
