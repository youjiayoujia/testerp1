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
        Schema::create('ProductImage', function (Blueprint $table) {
            $table->increments('id');//图片ID
            $table->integer('product_id')->nullable()->default(NULL);//产品ID
            $table->string('default_image')->nullable()->default(NULL);//默认主图
            $table->string('original_image')->nullable()->default(NULL);//原图
			$table->string('original_image_url')->nullable()->default(NULL);//原图下载地址
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
        Schema::drop('ProductImage');
    }
}
