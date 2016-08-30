<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEbayPublishProductDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ebay_publish_product_detail', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('publish_id')->comment('publish_id');
            $table->integer('product_id')->comment('product_id');
            $table->string('sku')->comment('ebay_sku');
            $table->float('start_price')->comment('售价');
            $table->integer('quantity')->comment('数量');
            $table->string('erp_sku')->comment('erp_sku');
            $table->string('quantity_sold')->comment('售出量');
            $table->string('item_id')->comment('产品id');
            $table->string('seller_id')->comment('所属销售');
            $table->enum('status', ['0', '1'])->comment('0 未在线 1在线')->default('0');
            $table->dateTime('start_time')->comment('上架时间');
            $table->dateTime('update_time')->comment('更新时间');
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
        Schema::drop('ebay_publish_product_detail');
    }
}
