<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEbayCasesListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ebay_cases_lists', function (Blueprint $table) {
            $table->increments('id');
            $table->string('case_id')->commit('caseID号')->nullable();
            $table->string('status')->commit('状态')->nullable();
            $table->string('type')->commit('类型')->nullable();
            $table->string('buyer_id')->commit('用户ID')->nullable();
            $table->string('seller_id')->commit('卖家ID')->nullable();
            $table->string('item_id')->commit('主题ID')->nullable();
            $table->string('item_title')->commit('主题标题')->nullable();
            $table->string('transaction_id')->commit('交易号')->nullable();
            $table->integer('case_quantity')->commit('纠纷数量')->nullable();
            $table->float('case_amount')->commit('纠纷总额')->nullable();
            $table->string('respon_date')->commit('响应日期')->nullable();
            $table->string('creation_date')->commit('创建日期')->nullable();
            $table->string('last_modify_date')->commit('修改日期')->nullable();
            $table->integer('assign_id')->commit('处理人')->nullable();
            $table->integer('account_id')->commit('渠道账号')->nullable();

            $table->string('global_id')->nullable();
            $table->string('open_reason')->nullable();
            $table->string('decision')->nullable();
            $table->string('decision_date')->nullable();
            $table->string('fvf_credited')->nullable();
            $table->float('agreed_renfund_amount')->nullable();
            $table->integer('buyer_expection')->nullable();
            $table->string('detail_status')->nullable();
            $table->string('tran_date')->nullable();
            $table->float('tran_price')->nullable();
            $table->mediumText('content')->commit('cases消息')->nullable();
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
        Schema::drop('ebay_cases_lists');
    }
}
