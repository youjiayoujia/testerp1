<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('model')->comment('model')->nullable()->default(NULL);
            $table->integer('spu_id')->comment('spu_id')->nullable()->default(0);
            $table->string('name')->comment('中文名')->nullable()->default(NUll);
            $table->string('c_name')->comment('英文名')->nullable()->default(NUll);
            $table->string('alias')->comment('别名')->nullable()->default(NULL);
            $table->string('alias_cname')->comment('别名英文')->nullable()->default(NUll);
            $table->string('catalog_id')->comment('分类id')->nullable()->default(0);
            $table->string('supplier_id')->comment('供应商id')->nullable()->default(0);
            $table->string('supplier_sku')->comment('供应商sku')->nullable()->default(NUll);
            $table->string('second_supplier_id')->comment('辅助供应商')->nullable()->default(0);
            $table->string('supplier_info')->comment('供应商信息')->nullable()->default(NULL);
            $table->string('purchase_url')->comment('采购链接')->nullable()->default(NUll);
            $table->string('product_sale_url')->comment('产品销售链接')->nullable()->default(NUll);
            $table->string('purchase_price')->comment('采购价')->nullable()->default(0);
            $table->string('purchase_carriage')->comment('运费')->nullable()->default(0);
            $table->string('product_size')->comment('产品尺寸')->nullable()->default(NUll);
            $table->string('package_size')->comment('包装尺寸')->nullable()->default(NULL);
            $table->string('upload_user')->comment('上传人')->nullable()->default(0);
            $table->string('default_image')->comment('默认图片')->nullable()->default(0);
            $table->string('weight')->comment('重量')->nullable()->default(0);
            $table->string('status')->comment('上下架状态')->nullable()->default(0);
            $table->string('remark')->comment('备注')->nullable()->default(NULL);
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
        Schema::drop('products');
    }
}
