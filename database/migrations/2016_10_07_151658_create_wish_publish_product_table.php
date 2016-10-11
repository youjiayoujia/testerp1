<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWishPublishProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('joom_publish_product', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('account_id')->comment('账号id');
            $table->string('productID')->comment('joom产品id');
            $table->dateTime('publishedTime')->comment("刊登时间");
            $table->string('status')->comment('是否下架 0 下架 1未下架');
            $table->enum('is_promoted', ['0', '1'])->comment('0  不促销 2 促销')->default('0');
            $table->string('review_status')->comment('平台状态');
            $table->string('sellerID')->comment('所属销售');
            $table->text('product_description')->comment('描述');
            $table->string('product_name')->comment('标题');
            $table->string('parent_sku')->comment('父SKU');
            $table->string('tags')->comment('产品Tags');
            $table->enum('product_type_status', [ '1','2','3'])->comment('1 草稿 2 在线 3待发布')->default('1');
            $table->string('brand')->comment('品牌');
            $table->string('landing_page_url')->comment('查询产品详情地址');
            $table->string('upc')->comment('UPC');
            $table->integer('number_saves')->comment('产品保存量');
            $table->integer('number_sold')->comment('产品销售量');
            $table->text('extra_images')->comment('额外图片');
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
        Schema::drop('joom_publish_product');
    }
}
