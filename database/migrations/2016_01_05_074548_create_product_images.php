<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductImages extends Migration
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
            $table->integer('spu_id')->comment('SPUID')->nullable()->default(null);
            $table->integer('product_id')->comment('产品ID')->nullable()->default(null);
            $table->string('type')->comment('图片类型（原图、默认图、各平台）')->nullable()->default(null);
            $table->string('path')->comment('图片存放文件夹地址')->nullable()->default(null);
            $table->string('name')->comment('图片名')->nullable()->default(null);
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
