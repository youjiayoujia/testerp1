<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWishPublishProductDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('joom_publish_product_detail', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('product_id')->comment('joom_publish_product id');
            $table->integer('account_id')->comment('账号id')->default('412');
            $table->string('productID')->comment('joom产品id');
            $table->string('product_sku_id')->comment('jiim产品sku的id');
            $table->string('sku')->comment('平台sku');
            $table->string('erp_sku')->comment('对应erp的sku');
            $table->string('sellerID')->comment('该sku对应销售');
            $table->string('price')->comment('售价');
            $table->string('inventory')->comment('数量');
            $table->string('color')->comment('颜色');
            $table->string('size')->comment('尺寸');
            $table->string('shipping')->comment('运费');
            $table->string('msrp')->comment('零售价');
            $table->string('shipping_time')->comment('运输时间');
            $table->string('main_image')->comment('sku 图片');
            $table->string('enabled')->comment('sku是否启用 0 未启用 1启用');
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
        Schema::drop('joom_publish_product_detail');
    }
}
