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
            $table->integer('catalog_id')->comment('分类id')->nullable()->default(0);
            $table->integer('supplier_id')->comment('供应商id')->nullable()->default(0);
            $table->string('supplier_sku')->comment('供应商sku')->nullable()->default(NUll);
            $table->string('second_supplier_id')->comment('辅助供应商')->nullable()->default(0);
            $table->string('supplier_info')->comment('供应商信息')->nullable()->default(NULL);
            $table->string('purchase_url')->comment('采购链接')->nullable()->default(NUll);
            $table->string('product_sale_url')->comment('产品销售链接')->nullable()->default(NUll);
            $table->decimal('purchase_price',8,2)->comment('采购价')->nullable()->default(0);
            $table->decimal('purchase_carriage',6,2)->comment('运费')->nullable()->default(0);
            $table->string('product_size')->comment('产品尺寸')->nullable()->default(NUll);
            $table->string('carriage_limit')->comment('运费限制')->nullable()->default(NULL);
            $table->string('package_limit')->comment('物流限制')->nullable()->default(NULL);
            $table->string('package_size')->comment('包装尺寸')->nullable()->default(NULL);
            $table->integer('upload_user')->comment('上传人')->nullable()->default(0);
            $table->string('default_image')->comment('默认图片')->nullable()->default(0);
            $table->string('size_description')->comment('尺码描述')->nullable()->default(NULL);
            $table->string('description')->comment('描述')->nullable()->default(NULL);
            $table->decimal('weight',5,2)->comment('重量')->nullable()->default(0);
            $table->string('status')->comment('上下架状态')->nullable()->default(0);
            $table->string('edit_status')->comment('编辑状态')->nullable()->default(NULL);
            $table->string('examine_status')->comment('审核状态')->nullable()->default(NULL);
            $table->string('remark')->comment('备注')->nullable()->default(NULL);
            $table->string('image_edit_not_pass_remark')->comment('图片审核不通过备注')->nullable()->default(NULL);
            $table->string('data_edit_not_pass_remark')->comment('资料审核不通过备注')->nullable()->default(NULL);
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
