<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateErpSalesPlatformTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('erp_sales_platform', function (Blueprint $table) {
            $table->increments('platID');
            $table->string('platTitle')->comment('平台标题');
            $table->float('platOperateFee')->comment('平台操作费率')->default(0);
            $table->float('platFeeRate')->comment('平台费率')->default(0);
            $table->float('maxForDiscount')->comment('最大折扣')->default(0);
            $table->float('platFeeDiscount')->comment('最小折扣')->default(0);          
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
        Schema::drop('erp_sales_platform');
    }
}
