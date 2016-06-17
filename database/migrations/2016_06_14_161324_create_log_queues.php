<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLogQueues extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('log_queues', function (Blueprint $table) {
            $table->increments('id');
            $table->string('relation_id')->comment('关联ID');
            $table->string('queue')->comment('队列');
            $table->string('description')->comment('描述');
            $table->string('lasting')->comment('执行时间');
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
        Schema::drop('log_queues');
    }
}
