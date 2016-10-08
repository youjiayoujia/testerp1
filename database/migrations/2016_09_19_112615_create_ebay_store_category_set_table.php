<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEbayStoreCategorySetTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ebay_store_category_set', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('site')->comment('站点')->default(0);
            $table->integer('warehouse')->comment('仓库')->default(1);
            $table->integer('category')->comment('erp分类')->default(0);
            $table->text('category_description')->comment('对应账号的店铺分类');
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
        Schema::drop('ebay_store_category_set');
    }
}
