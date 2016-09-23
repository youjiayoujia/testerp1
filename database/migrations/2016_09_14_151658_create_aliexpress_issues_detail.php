<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAliexpressIssuesDetail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('aliexpress_issues_detail', function (Blueprint $table) {
            $table->increments('id');
            $table->string('issue_list_id')->comment('纠纷列表id');
            $table->string('resultMemo')->nullable()->comment('api回调状态');
            $table->string('orderId')->nullable();
            $table->string('gmtCreate')->nullable();
            $table->integer('issueReasonId')->nullable()->comment('纠纷原因ID');
            $table->string('buyerAliid')->nullable()->comment('买家memberID');
            $table->string('issueStatus')->nullable()->comment('纠纷状态');
            $table->string('issueReason')->nullable()->comment('纠纷原因');
            $table->string('productName')->nullable()->comment('产品名称');
            $table->mediumText('productPrice')->nullable()->comment('产品价格');
            $table->mediumText('buyerSolutionList')->nullable()->comment('买家协商方案');
            $table->mediumText('sellerSolutionList')->nullable()->comment('卖家协商方案');
            $table->mediumText('platformSolutionList')->nullable()->comment('平台协商方案');
            $table->mediumText('refundMoneyMax')->nullable()->comment('退款上限');
            $table->mediumText('refundMoneyMaxLocal')->nullable()->comment('退款上限本币');
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
        Schema::drop('aliexpress_issues_detail');
    }
}
