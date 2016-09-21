<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAliexpressIssuesList extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('aliexpress_issues_list', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('account_id');
            $table->string('issue_id')->nullable()->comment('纠纷ID');
            $table->string('issueStatus')->nullable('当前状态');
            $table->string('issueType')->nullable()->comment('纠纷状态：WAIT_SELLER_CONFIRM_REFUND 买家提起纠纷,SELLER_REFUSE_REFUND 卖家拒绝纠,ACCEPTISSUE 卖家接受纠纷,WAIT_BUYER_SEND_GOODS 等待买家发货,WAIT_SELLER_RECEIVE_GOODS 买家发货，等待卖家收货,ARBITRATING 仲裁中,SELLER_RESPONSE_ISSUE_TIMEOUT 卖家响应纠纷超时');
            $table->string('gmtCreate')->nullable('创建时间');
            $table->string('issueProcessDTOs')->nullable();
            $table->string('reasonChinese')->nullable();
            $table->string('orderId')->nullable();
            $table->string('reasonEnglish')->nullable();
            $table->string('gmtModified')->nullable();
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
        Schema::drop('aliexpress_issues_list');
    }
}
