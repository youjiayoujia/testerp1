<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductEnglishValue extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_english_values', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('product_id')->comment('产品ID')->nullable()->default(null);
            $table->string('choies_set')->comment('分类')->nullable()->default(null);
            $table->string('name')->comment('中文名')->nullable()->default(null);
            $table->string('c_name')->comment('英文名')->nullable()->default(null);
            $table->string('attribute_size')->comment('属性')->nullable()->default(null);
            $table->string('store')->comment('store')->nullable()->default(null);
            $table->string('brief')->comment('简述')->nullable()->default(null);
            $table->string('description')->comment('描述')->nullable()->default(null);
            $table->string('filter_attributes')->nullable()->default(null);
            $table->float('weight')->comment('重量')->nullable()->default(0);
            $table->string('unedit_reason')->comment('图片不编辑原因')->nullable()->default(null);
            $table->string('sale_usd_price')->comment('产品name')->nullable()->default(null);
            $table->string('market_usd_price')->comment('产品中文name')->nullable()->default(null);
            $table->integer('cost_usd_price')->comment('供应商')->nullable()->default(null);
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
        Schema::drop('product_english_values');
    }
}
