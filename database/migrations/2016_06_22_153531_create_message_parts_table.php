<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMessagePartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('message_parts',function(Blueprint $table){
            $table->increments('id');
            $table->integer('message_id')->nullable()->defalut();
            $table->integer('parent_id')->nullable()->defalut();
            $table->integer('part_id')->nullable()->defalut();
            $table->string('mime_type')->nullable()->defalut();
            $table->text('headers')->nullable()->defalut();
            $table->string('filename')->nullable()->defalut();
            $table->text('attachment_id')->nullable()->defalut();
            $table->text('body')->nullable()->defalut();
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
