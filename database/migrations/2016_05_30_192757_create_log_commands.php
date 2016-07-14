<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLogCommands extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('log_commands', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('relation_id')->comment('关联ID');
            $table->string('signature')->comment('命令');
            $table->longText('data')->comment('数据');
            $table->string('description')->comment('描述');
            $table->float('lasting')->comment('执行时间');
            $table->integer('total')->comment('计数');
            $table->string('result')->comment('结果');
            $table->string('remark')->comment('备注');
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
        Schema::drop('log_commands');
    }
}
