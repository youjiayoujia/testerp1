<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSmtProductSkusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::create('smt_product_skus', function (Blueprint $table) {
            $table->increments('id');
            $table->string('skuMark')->comment('格式为 productId-skuCode，主要是更新数据时候做唯一标识');
            $table->string('skuCode')->comment('产品SKU');
            $table->string('smtSkuCode')->comment('速卖通SKU');
            $table->string('productId')->comment('产品的ID');
            $table->string('sku_active_id')->comment('sku对应id');
            $table->decimal('skuPrice')->comment('sku价格');
            $table->decimal('skuStock')->comment('毛重');
            $table->integer('skuPropertyId');
            $table->string('propertyValueDefinitionName')->comment('属性名');
            $table->decimal('profitRate')->comment('利润率(%)');
            $table->dateTime('synchronizationTime')->comment('数据同步时间');
            $table->integer('isRemove')->comment('是否被移除')->default('0');
            $table->dateTime('last_turndown_date')->comment('下调日期');
            $table->integer('is_new')->comment('是否是新的，1-新的');
            $table->integer('is_erp')->comment('广告sku是否和erp sku对应上,0-没对应上，1-对应上');
            $table->integer('ipmSkuStock')->comment('实际可售库存');
            $table->text('aeopSKUProperty')->comment('SKU属性，序列化后保存');
            $table->integer('overSeaValId')->comment('海外仓属性的值的ID');
            $table->decimal('lowerPrice')->comment('计算出来的最低售价');
            $table->tinyInteger('updated');
            $table->tinyInteger('discountRate')->comment('按原价和最低售价计算的折扣率');
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
