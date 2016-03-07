<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductRequire extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_requires', function (Blueprint $table) {
            $table->increments('id');
            $table->text('img1')->comment('图片1')->nullable()->default(NULL);
            $table->text('img2')->comment('图片2')->nullable()->default(NULL);
            $table->text('img3')->comment('图片3')->nullable()->default(NULL);
            $table->text('img4')->comment('图片4')->nullable()->default(NULL);
            $table->text('img5')->comment('图片5')->nullable()->default(NULL);
            $table->text('img6')->comment('图片6')->nullable()->default(NULL);
            $table->string('name',128)->comment('产品名字')->default(NULL);
            $table->string('province')->comment('省')->default(NULL);
            $table->string('city')->comment('市')->default(NULL);
            $table->string('similar_sku')->comment('相似的sku')->nullable()->default(NULL);
            $table->string('competition_url')->comment('竞争产品的url')->nullable()->default(NULL);
            $table->text('remark')->comment('备注信息说明')->nullable()->default(NULL);
            $table->date('expected_date')->comment('希望上传日期');
            $table->integer('needer_id')->comment('需求人id')->default(NULL);
            $table->integer('needer_shop_id')->comment('需求店铺id')->default(NULL);
            $table->string('created_by')->comment('创建人')->default(NULL);
            $table->enum('status',['0', '1', '2'])->comment('处理状态')->default('0');
            $table->integer('user_id')->comment('处理人id')->nullable()->default(NULL);
            $table->date('handle_time')->comment('处理时间')->nullable()->default(NULL);
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
        Schema::drop('product_require');
    }
}
