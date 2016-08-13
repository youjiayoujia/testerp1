<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEbaySpecificsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ebay_specifics', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->default('')->coment('名称');
            $table->integer('category_id')->coment('分类id');
            $table->integer('site')->coment('站点');
            $table->string('value_type')->default('')->coment('值类型');
            $table->string('min_values')->default('')->coment('最小值数');
            $table->string('max_values')->default('')->coment('最大值数');
            $table->string('selection_mode')->default('')->coment('值形式');
            $table->string('variation_specifics')->default('')->coment('是否支持多属性');
            $table->text('specific_values')->default('')->coment('默认提供值');
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
        Schema::drop('ebay_specifics');
    }
}
