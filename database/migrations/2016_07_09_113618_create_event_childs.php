<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventChilds extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event_childs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('parent_id')->comment('父id')->default(0);
            $table->integer('type_id')->comment('类型id')->default(0);
            $table->string('what')->comment('什么事情')->default(NULL);
            $table->timestamp('when')->comment('时间')->default(NULL);
            $table->integer('who')->comment('操作人')->default(0);
            $table->text('from_arr')->comment('原始数据 serialize')->default(NULL);
            $table->text('to_arr')->comment('修改后数据 serialize')->default(NULL);
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
        Schema::drop('event_childs');
    }
}
