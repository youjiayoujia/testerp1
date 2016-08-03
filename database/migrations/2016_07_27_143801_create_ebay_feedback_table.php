<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEbayFeedbackTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ebay_feedback', function (Blueprint $table) {
            $table->increments('id');
            $table->string('feedback_id')->comment('FeedBackID');
            $table->integer('channel_account_id')->comment('账号');
            $table->string('commenting_user')->coment('人员');
            $table->integer('commenting_user_score')->comment('评分');
            $table->string('comment_text')->comment('内容');
            $table->string('comment_type')->comment('类型');
            $table->string('ebay_item_id')->comment('ebay产品id');
            $table->string('transaction_id')->comment('交易号');
            $table->dateTime('comment_time')->comment('Feedback提交时间');
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
        Schema::drop('ebay_feedback');
    }
}
