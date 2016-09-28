<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateErpLazadaProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::create('erp_lazada_products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('sellerSku')->comment('卖方的唯一标识符');
            $table->string('shopSku')->comment('商店的唯一标识符');
            $table->string('sku')->coment('对应erp的sku');
            $table->string('name')->comment('产品名称');
            $table->string('variation')->comment('产品的变化');
            $table->tinyInteger('quantity')->comment('可用库存');
            $table->float('price')->comment('产品的正常价格');
            $table->float('salePrice')->comment('产品的特殊销售价格');
            $table->dateTime('saleStartDate')->comment('特殊销售的开始时间');
            $table->dateTime('saleEndDate')->comment('特殊销售的结束时间');
            $table->string('status')->comment('产品的状态:active inactive deleted');
            $table->string('productId')->comment('EAN / UPC/ ISBN产物，如果存在');
            $table->string('account')->comment('销售账号');
            
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
        Schema::drop('erp_lazada_products');
    }
}
