<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSmtCategoryListTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
          Schema::create('smt_category_list', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('category_id')->comment('线上产品分类id');
            $table->string('category_name')->comment('产品分类名称');
            $table->string('category_name_en')->comment('产品分类英文名称');    
            $table->integer('pid')->comment('产品分类父id');
            $table->tinyInteger('level')->comment('级别')->default('0');
            $table->tinyInteger('isleaf')->comment('子叶类')->default('0');
            $table->dateTime('last_update_time')->comment('最后更新时间');
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
        Schema::drop('smt_category_list');
    }
}
