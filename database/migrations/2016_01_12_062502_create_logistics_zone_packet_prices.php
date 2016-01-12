<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLogisticsZonePacketPrices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('logistics_zone_packet_prices', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->comment('物流分区报价')->default(NULL);
            $table->string('shipping')->comment('种类')->default(NULL);
            $table->float('price')->comment('价格')->default(NULL);
            $table->float('other_price')->comment('其他费用')->default(NULL);
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
        Schema::drop('logistics_zone_packet_prices');
    }
}
