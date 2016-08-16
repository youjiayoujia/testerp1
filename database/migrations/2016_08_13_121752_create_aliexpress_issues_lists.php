<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAliexpressIssuesLists extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('aliexpress_issues_lists', function (Blueprint $table) {
            $table->increments('id');
            $table->string('issue_id')->comment('纠纷id')->nullable();
            $table->string('gmt_modified')->comment('修改时间')->nullable();
            $table->string('gmt_create')->comment('创建时间')->nullable();
            $table->string('reason_cn')->comment('中文原因')->nullable();
            $table->string('reason_en')->comment('英文原因')->nullable();
            $table->string('channel_order_id')->comment('平台订单号')->nullable();
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
        Schema::drop('aliexpress_issues');
    }
}
