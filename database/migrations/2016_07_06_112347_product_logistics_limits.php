<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ProductLogisticsLimits extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_logistics_limits', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('product_id')->comment('product_id')->default(NULL);
            $table->integer('logistics_limits_id')->comment('product_id')->default(NULL);
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
        Schema::drop('product_logistics_limits');
    }
}
