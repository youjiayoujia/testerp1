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
        Schema::create('FashionSel', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name',128)->default(NULL);
            $table->string('color',8)->default(NULL);
            $table->integer('category_id')->default(NULL);
            $table->string('address')->default(NULL);
            $table->string('similar_sku')->default(NULL);
            $table->string('competition_url')->default(NULL);

            $table->text('add_des')->default(NULL);
            
            $table->date('expected_date');
            $table->integer('needer_id')->default(NULL);
            $table->integer('needer_shopid')->default(NULL);
            
            $table->enum('status',['未处理', '未找到', '已找到'])->default('未处理');
            $table->integer('user_id')->default(NULL);
            $table->date('handle_time')->default(NULL);
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
