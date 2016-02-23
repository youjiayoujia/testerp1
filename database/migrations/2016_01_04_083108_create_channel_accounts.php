<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChannelAccounts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('channel_accounts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->comment('渠道账号')->default("");
            $table->string('alias')->comment('渠道账号别名')->default("");
            $table->integer('channel_id')->comment('渠道');
            $table->integer('country_id')->comment('所在国家');
            $table->string('domain')->comment('账号对应域名')->default("");
            $table->integer('sync_cycle')->comment('订单同步周期')->default("1");
            $table->string('activate')->comment('是否激活')->default("N");

            $table->integer('default_businesser_id')->comment('默认运营人员');
            $table->integer('default_server_id')->comment('默认客服人员');

            $table->string('email')->comment('客服邮箱地址')->default("");
            $table->string('delivery_warehouse')->comment('默认发货仓库')->default("");
            $table->string('merge_package')->comment('合并相同地址订单包裹:')->default("N");
            $table->string('thanks')->comment('是否打印感谢信:')->default("N");
            $table->string('picking_list')->comment('是否打印拣货单')->default("N");
            $table->string('generate_sku')->comment('是否无规则生成渠道SKU')->default("N");
            $table->string('image_site')->comment('产品图片域名')->default("");
            $table->string('clearance')->comment('可否通关')->default("N");
            $table->string('tracking_config')->comment('上传追踪号配置')->default("");
            $table->string('order_prefix')->comment('订单前缀')->default("");

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('channel_account_user', function (Blueprint $table) {

            $table->integer('channel_account_id')->unsigned()->index();
            $table->foreign('channel_account_id')->references('id')->on('channel_accounts')->onDelete('cascade');

            $table->integer('user_id')->unsigned()->index();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

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
        Schema::drop('channel_accounts');
    }
}
