<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMessageRepliesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('message_replies',function (Blueprint $table){
            $table->increments('id');
            $table->integer('message_id')->default(NULL);
            $table->string('to');
            $table->string('to_email');
            $table->string('title')->default(NULL);
            $table->text('content');
            $table->enum('status',['NEW','SENT','FAIL'])->defalut('NEW');
            $table->string('updatefile')->defalut(NULL);
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
