<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchaseItemArrivalLogs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_item_arrival_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('sku')->comment('sku')->default(NULL);
            $table->integer('purchase_item_id')->comment('采购条目id')->default(0);
            $table->integer('arrival_num')->comment('到货数量')->default(0);
            $table->integer('good_num')->comment('优品')->default(0);
            $table->integer('bad_num')->comment('误差')->default(0);
            $table->dateTime('quality_time')->comment('质检时间')->default(NULL);
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
        Schema::drop('purchase_item_arrival_logs');
    }
}
