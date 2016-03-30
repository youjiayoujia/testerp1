<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAliexpressProduct extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('aliexpress_products', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('product_id')->comment('产品ID')->nullable()->default(null);
            $table->string('choice_info')->comment('平台信息')->nullable()->default(null);
            $table->string('name')->comment('产品name')->nullable()->default(null);
            $table->string('c_name')->comment('产品中文name')->nullable()->default(null);
            $table->integer('supplier_id')->comment('供应商')->nullable()->default(null);
            $table->string('supplier_info')->comment('供应商信息')->nullable()->default(null);
            $table->string('supplier_sku')->comment('供应商sku')->nullable()->default(null);
            $table->string('product_sale_url')->comment('供应商sku对应的url')->nullable()->default(null);
            $table->float('purchase_price')->comment('采购价')->nullable()->default(null);
            $table->float('purchase_carriage')->comment('物流费')->nullable()->default(null);
            $table->float('weight')->comment('重量')->nullable()->default(null);
            $table->string('fabric')->comment('材质')->nullable()->default(NULL);
            $table->string('remark')->comment('备注')->nullable()->default(null);
            $table->string('image_remark')->comment('图片备注')->nullable()->default(null);
            $table->tinyInteger('status')->comment('审核状态')->nullable()->default(0);
            $table->tinyInteger('edit_status')->comment('编辑状态')->nullable()->default(0);
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
        Schema::drop('aliexpress_products');
    }
}
