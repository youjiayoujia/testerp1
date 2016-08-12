<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSmtTemplateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('smt_template', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('plat')->comment('平台');
            $table->integer('token_id')->comment('账号ID');
            $table->string('name')->comment('模板名称');
            $table->string('pic_path')->comment('效果图路径');
            $table->text('content')->comment('模板详情');
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
        //
    }
}
