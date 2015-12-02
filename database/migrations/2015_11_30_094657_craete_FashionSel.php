<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CraeteFashionSel extends Migration
{
    /**
     * Run the migrations.
     *
     *
     * @name, color, category_id, address, similar_sku(相似产品的sku), competition_url(竞争产品的url)
     * @expected_date(期待上传时间), needer_id,needer_shopid(需求人，需求店铺id)
     * @status(处理状态), user_id 处理人 handle_time(处理时间)
     * ---------上面为一些共有的属性，还可能有一些额外的属性，我们可通过add_des属性来添加------------
     * @add_des 添加备注信息
     *
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fashion_sel', function (Blueprint $table) {
            $table->increments('id');

            $table->text('img1')->comment('图片1')->nullable()->default(NULL);
            $table->text('img2')->comment('图片2')->nullable()->default(NULL);
            $table->text('img3')->comment('图片3')->nullable()->default(NULL);
            $table->text('img4')->comment('图片4')->nullable()->default(NULL);
            $table->text('img5')->comment('图片5')->nullable()->default(NULL);
            $table->text('img6')->comment('图片6')->nullable()->default(NULL);

            $table->string('name',128)->comment('产品名字')->default(NULL);
            $table->string('address')->comment('货源地')->default(NULL);
            $table->string('similar_sku')->comment('相似的sku')->nullable()->default(NULL);
            $table->string('competition_url')->comment('竞争产品的url')->nullable()->default(NULL);

            $table->text('remark')->comment('备注信息说明')->nullable()->default(NULL);
            
            $table->date('expected_date')->comment('希望上传日期');
            $table->integer('needer_id')->comment('需求人id')->default(NULL);
            $table->integer('needer_shopid')->comment('需求店铺id')->default(NULL);
            
            $table->enum('status',['未处理', '未找到', '已找到'])->comment('处理状态')->default('未处理');
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
        Schema::drop('FashionSel');
        
    }
}
