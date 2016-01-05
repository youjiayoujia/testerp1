<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductImageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('product_images', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('product_id')->comment('产品ID')->nullable()->default(NULL);
			$table->integer('user_id')->comment('操作者ID')->nullable()->default(NULL);
            $table->string('type')->comment('图片类型（原图、默认图、各平台）')->nullable()->default(NULL);
            $table->string('path')->comment('图片存放文件夹地址')->nullable()->default(NULL);
			$table->string('name')->comment('图片名')->nullable()->default(NULL);
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
        Schema::drop('product_images');
    }
}
