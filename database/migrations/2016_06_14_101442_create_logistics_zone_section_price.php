<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLogisticsZoneSectionPrice extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('logistics_zone_section_prices', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('logistics_zone_id')->comment('分区报价id')->default(0);
            $table->decimal('weight_from', '6', '2')->comment('开始重量')->default(0);
            $table->decimal('weight_to', '6', '2')->comment('结束重量')->default(0);
            $table->decimal('price', '6', '2')->comment('价格')->default(0);
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
        Schema::drop('logistics_zone_section_prices');
    }
}
