<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEbayCategoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ebay_category', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('category_id')->comment('分类id');
            $table->string('best_offer')->default('')->coment('是否支持议价');
            $table->string('auto_pay')->default('')->coment('是否立即付款');
            $table->integer('category_level')->coment('分类级别');
            $table->string('category_name')->default('')->coment('分类名');
            $table->integer('category_parent_id')->coment('上级分类id');
            $table->string('leaf_category')->default('')->coment('是否是末分类');
            $table->integer('site')->coment('站点');
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
        Schema::drop('ebay_category');
    }
}
