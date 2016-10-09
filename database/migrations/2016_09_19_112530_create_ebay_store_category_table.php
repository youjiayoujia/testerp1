<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEbayStoreCategoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ebay_store_category', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('account_id')->comment('账号id')->default(0);
            $table->string('store_category')->comment('店铺分类')->default('');
             $table->string('store_category_name')->comment('店铺分类名称')->default('');
            $table->integer('level')->comment('级别')->default(0);
            $table->string('category_parent')->comment('父id')->default('');
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
        Schema::drop('ebay_store_category');
    }
}
