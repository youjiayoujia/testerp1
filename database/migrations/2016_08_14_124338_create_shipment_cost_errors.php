<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShipmentCostErrors extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shipment_cost_errors', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('parent_id')->comment('父id')->default(0);
            $table->string('hang_num')->comment('挂号码')->default(NULL);
            $table->string('channel_name')->comment('渠道名称')->default(NULL);
            $table->text('remark')->comment('备注')->default(NULL);
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
        Schema::drop('shipment_cost_errors');
    }
}
