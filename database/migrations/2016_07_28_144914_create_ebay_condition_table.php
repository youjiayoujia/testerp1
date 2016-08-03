<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEbayConditionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ebay_condition', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('condition_id')->comment('物品状态id');
            $table->string('condition_name')->default('')->coment('名称');
            $table->integer('category_id')->coment('分类id');
            $table->integer('site')->coment('站点');
            $table->string('is_variations')->default('')->coment('是否支持多属性');
            $table->string('is_condition')->default('')->coment('物品状况是否启用');
            $table->string('is_upc')->default('')->coment('是否需要upc');
            $table->string('is_ean')->default('')->coment('是否需要ean');
            $table->string('is_isbn')->default('')->coment('是否需要isbn');
            $table->dateTime('last_update_time')->coment('最后更新时间');
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
        Schema::drop('ebay_condition');
    }
}
