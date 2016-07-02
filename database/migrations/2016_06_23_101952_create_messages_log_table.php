<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMessagesLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messages_log',function(Blueprint $table){
            $table->increments('id');
            $table->integer('message_id')->nullable()->default(NULL);
            $table->integer('assign_id')->nullable()->default(NULL);
            $table->string('foruser')->nullable()->default(NULL);
            $table->string('touser')->nullable()->default(NULL);
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
