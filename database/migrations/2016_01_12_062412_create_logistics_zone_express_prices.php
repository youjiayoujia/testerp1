<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLogisticsZoneExpressPrices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('logistics_zone_express_prices', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->comment('物流分区报价')->default(NULL);
            $table->string('shipping')->comment('种类')->default(NULL);
            $table->float('fixed_weight')->comment('首重')->default(NULL);
            $table->float('fixed_price')->comment('首重价格')->default(NULL);
            $table->float('continued_weight')->comment('续重')->default(NULL);
            $table->float('continued_price')->comment('续重价格')->default(NULL);
            $table->float('other_fixed_price')->comment('其他固定费用')->default(NULL);
            $table->float('other_scale_price')->comment('其他比例费用')->default(NULL);
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
        Schema::drop('logistics_zone_express_prices');
    }
}
