<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('account_id')->default(NULL);
            $table->integer('type_id');
            $table->integer('list_id')->defalut(NULL);
            $table->string('message_id')->defalut(NULL);
            $table->integer('assign_id')->defalut(NULL);
            $table->enum('status', ['UNREAD', 'PROCESS', 'COMPLETE'])->defalut('UNREAD');
            $table->text('labels')->defalut(NULL);
            $table->string('label')->default(NULL);
            $table->string('from')->default(NULL);
            $table->string('from_name')->default(NULL);
            $table->string('to')->default(NULL);
            $table->string('date')->default(NULL);
            $table->text('subject')->default(NULL);
            $table->text('title_email')->default(NULL);
            $table->enum('related', ['0', '1'])->default(0);
            $table->enum('required', ['0', '1'])->default(1);
            $table->enum('read', ['0', '1'])->default(0);
            $table->timestamp('start_at')->default(NULL);
            $table->timestamp('end_at')->default(NULL);
            $table->integer('dont_reply')->default(0);
            $table->string('content')->default(NULL);
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
