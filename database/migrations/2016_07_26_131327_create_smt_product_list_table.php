<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSmtProductListTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('smt_product_list', function (Blueprint $table) {
            $table->increments('id');
            $table->string('product_url');
            $table->integer('token_id')->comment('账号id');
            $table->string('productId')->comment('产品id');
            $table->integer('user_id')->comment('刊登人员的ID');
            $table->string('subject')->comment('产品标题');
            $table->decimal('productPrice')->comment('产品价格');
            $table->decimal('productMinPrice')->comment('最小价');
            $table->decimal('productMaxPrice')->comment('最大价');
            $table->string('productStatusType')->comment('商品业务状态，目前提供4种，输入参数分别是：上架:onSelling ；下架:offline ；审核中:auditing ；审核不通过:editingRequired。');
            $table->integer('groupId')->comment('商品分组id');
            $table->integer('categoryId')->comment('商品分类id');
            $table->integer('packageLength')->comment('长度');
            $table->integer('packageWidth')->comment('宽度');
            $table->integer('packageHeight')->comment('高度');
            $table->integer('grossWeight')->comment('重量');
            $table->smallInteger('deliveryTime')->comment('多久发货');
            $table->smallInteger('wsValidNum')->comment('刊登天数,到期后自动下架');
            $table->tinyInteger('multiattribute')->comment('0为单属性，1为多属性');
            $table->tinyInteger('isRemove')->comment('是否被移除');
            $table->integer('old_token_id')->comment('旧的账号ID，刊登成功时会清除');
            $table->string('old_productId')->comment('旧的产品ID，刊登成功时清除');
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
