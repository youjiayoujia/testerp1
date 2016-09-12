<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSkuMessages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sku_messages', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('sku_id')->comment('sku_id');
            $table->string('question_group')->comment('问题所属分类');
            $table->string('image')->comment('图片');
            $table->string('question')->coment('提问');
            $table->timestamp('question_time')->comment('提问时间');
            $table->integer('question_user')->comment('提问人');
            $table->string('answer')->comment('解答');
            $table->timestamp('answer_date')->comment('解答日期');
            $table->integer('answer_user')->comment('解答人');
            $table->string('extra_question')->coment('追加问题');
            $table->string('status')->comment('状态');
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
        Schema::drop('sku_messages');
    }
}
