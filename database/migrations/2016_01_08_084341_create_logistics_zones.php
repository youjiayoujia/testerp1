<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLogisticsZones extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('logistics_zones', function (Blueprint $table) {
            $table->increments('id');
            $table->string('zone')->comment('物流分区报价');
            $table->integer('logistics_id')->comment('物流方式');
            $table->string('country_id')->comment('国家');
            $table->string('shipping_id')->comment('种类')->default(NULL);
            $table->float('fixed_weight')->comment('首重')->nullable()->default(NULL);
            $table->float('fixed_price')->comment('首重价格')->nullable()->default(NULL);
            $table->float('continued_weight')->comment('续重')->nullable()->default(NULL);
            $table->float('continued_price')->comment('续重价格')->nullable()->default(NULL);
            $table->float('other_fixed_price')->comment('其他固定费用')->nullable()->default(NULL);
            $table->float('other_scale_price')->comment('其他比例费用')->nullable()->default(NULL);
            $table->float('price')->comment('价格')->nullable()->default(NULL);
            $table->float('other_price')->comment('其他费用')->nullable()->default(NULL);
            $table->float('discount')->comment('最后折扣')->default(NULL);
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
        Schema::drop('logistics_zones');
    }
}
