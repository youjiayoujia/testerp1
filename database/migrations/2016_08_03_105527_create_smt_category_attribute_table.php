<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSmtCategoryAttributeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('smt_category_attribute', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('category_id')->comment('分类ID');
            $table->mediumText('attribute')->comment('属性,保存的序列化信息');
            $table->datetime('last_update_time')->comment('最后更新时间');            
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
         Schema::drop('smt_category_attribute');
    }
}
