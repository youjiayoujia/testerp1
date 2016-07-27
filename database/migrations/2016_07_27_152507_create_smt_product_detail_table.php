<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSmtProductDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::create('smt_product_detail', function (Blueprint $table) {
            $table->increments('id');
            $table->string('productId')->comment('产品id');
            $table->text('aeopAeProductPropertys')->comment('产品属性，序列化后保存');
            $table->string('imageURLs')->comment('产品图片,以分号分隔');
            $table->string('detail')->comment('详情描述(注意过滤)');
            $table->string('keyword')->comment('关键字');
            $table->string('productMoreKeywords1')->comment('更多关键字1');
            $table->string('productMoreKeywords2')->comment('更多关键字2');
            $table->integer('productUnit')->comment('单位ID');
            $table->tinyInteger('isImageDynamic')->default('0')->comment('动态主图 ,1是');
            $table->tinyInteger('isImageWatermark')->default('0')->comment('是否水印');
            $table->integer('lotNum')->comment('每包件数');
            $table->integer('bulkOrder')->comment('批发最小数量');
            $table->tinyInteger('packageType')->comment('是否打包销售');
            $table->tinyInteger('isPackSell')->comment('是否自定义记重');
            $table->tinyInteger('bulkDiscount')->comment('批发折扣');
            $table->integer('promiseTemplateId')->comment('服务模板ID');
            $table->integer('freightTemplateId')->comment('运费模板ID');
            $table->integer('templateId')->comment('自定义的模板ID');
            $table->integer('shouhouId')->comment('自定义售后模板ID');
            $table->text('detail_title')->comment('自定义描述标题');
            $table->integer('sizechartId')->comment('尺寸模板');
            $table->string('src')->comment('产品来源');
            $table->text('detailPicList')->comment('详情描述图片');
            $table->text('detailLocal')->comment('本地刊登时的详情信息');
            $table->string('relationProductIds')->comment('关联产品ID');
            $table->string('relationLocation')->comment('关联产品的位置');
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
        //
    }
}
