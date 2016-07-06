<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEbayShippingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ebay_shipping', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('site_id')->comment('站点id')->default(0);
            $table->string('description')->comment('描述名称')->default('');
            $table->enum('international_service', ['1', '2'])->comment('1 国际 2 国内')->default('1');
            $table->string('shipping_service')->comment('物流api名称')->default('');
            $table->integer('shipping_service_id')->comment('物流id')->default(0);
            $table->integer('shipping_time_max')->comment('运输最大时间天')->default(0);
            $table->integer('shipping_time_min')->comment('运输最小时间天')->default(0);
            $table->enum('valid_for_selling_flow', ['1', '2'])->comment('1 api可以使用该物流 2 不能使用')->default('1');
            $table->string('shipping_category')->comment('物流分类')->default('');
            $table->string('shipping_carrier')->comment('物流承运商')->default('');

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
        Schema::drop('ebay_shipping');
    }
}
