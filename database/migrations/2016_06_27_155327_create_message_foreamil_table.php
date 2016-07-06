<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMessageForeamilTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('message_foremail',function (Blueprint $table){
            $table->increments('id');
            $table->integer('assign_id')->nullable()->default(NULL);
            $table->integer('message_id')->nullable()->default(NULL);
            $table->string('to');
            $table->string('to_email');
            $table->string('to_useremail')->nullable()->default(NULL);
            $table->string('title')->nullable()->default(NULL);
            $table->text('context')->nullable()->default(NULL);
            $table->enum('status',['NEW','FAIL','SENT'])->default('NEW');
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
