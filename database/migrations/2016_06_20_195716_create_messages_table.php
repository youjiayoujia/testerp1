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
            $table->integer('account_id')->nullable()->default(NULL);
            $table->integer('channel_id')->default(NULL)->comment('渠道ID');
            $table->integer('type_id');
            $table->integer('list_id')->nullable()->defalut(NULL);
            $table->string('message_id')->nullable()->defalut(NULL);
            $table->integer('assign_id')->nullable()->defalut(NULL);
            $table->enum('status', ['UNREAD', 'PROCESS', 'COMPLETE'])->defalut('UNREAD')->nullable();
            $table->text('labels')->nullable()->defalut(NULL);
            $table->string('label')->nullable()->default(NULL);
            $table->string('from')->nullable()->default(NULL);
            $table->string('from_name')->nullable()->default(NULL);
            $table->string('to')->nullable()->default(NULL);
            $table->string('date')->nullable()->default(NULL);
            $table->text('subject')->nullable()->default(NULL);
            $table->text('title_email')->nullable()->default(NULL);
            $table->enum('related', ['0', '1'])->nullable()->default(0);
            $table->enum('required', ['0', '1'])->nullable()->default(1);
            $table->enum('read', ['0', '1'])->nullable()->default(0);
            $table->timestamp('start_at')->nullable()->default(NULL);
            $table->timestamp('end_at')->nullable()->default(NULL);
            $table->integer('dont_reply')->nullable()->default(0);
            $table->mediumText('content')->nullable()->default(NULL);
            $table->text('channel_message_fields')->nullable()->comment('渠道信息数据数组')->default(NULL);
            $table->string('channel_order_number')->nullable()->comment('平台订单号');
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
