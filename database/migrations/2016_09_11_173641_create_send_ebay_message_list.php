<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSendEbayMessageList extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('send_ebay_message_list', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('operate_id')->comment('操作人');
            $table->integer('order_id')->comment('订单号');
            $table->string('title')->nullable()->comment('标题');
            $table->string('content')->nullable()->comment('内容');
            $table->enum('is_send', ['0', '1'])->comment('是否发送成功，1为成功')->default('0');
            $table->string('itemids')->nullable();
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
        Schema::drop('send_ebay_message_list');
    }
}
