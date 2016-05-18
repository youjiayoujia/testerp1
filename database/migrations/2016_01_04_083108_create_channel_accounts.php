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
            $table->string('account')->comment('渠道账号');
            $table->string('alias')->comment('渠道账号别名');
            $table->integer('channel_id')->comment('渠道');
            $table->integer('country_id')->comment('所在国家');
            $table->string('order_prefix')->comment('订单前缀');
            $table->float('sync_cycle')->comment('订单同步周期');
            $table->string('domain')->comment('账号对应域名');
            $table->string('image_domain')->comment('产品图片域名');
            $table->string('service_email')->comment('客服邮箱地址');
            $table->string('tracking_config')->comment('上传追踪号配置');
            $table->string('amazon_api_url')->comment('AWS Service Url');
            $table->string('amazon_marketplace_id')->comment('AWS MarketplaceId');
            $table->string('amazon_seller_id')->comment('AWS SellerId');
            $table->string('amazon_accesskey_id')->comment('AWS AWSAccessKeyId');
            $table->string('amazon_accesskey_secret')->comment('AWS AWS_SECRET_ACCESS_KEY');
            $table->integer('operator_id')->comment('默认运营人员');
            $table->integer('customer_service_id')->comment('默认客服人员');
            $table->enum('is_merge_package', ['0', '1'])->comment('合并相同地址订单包裹')->default('0');
            $table->enum('is_thanks', ['0', '1'])->comment('是否打印感谢信')->default('0');
            $table->enum('is_picking_list', ['0', '1'])->comment('是否打印拣货单')->default('0');
            $table->enum('is_rand_sku', ['0', '1'])->comment('是否随机生成渠道SKU')->default('0');
            $table->enum('is_clearance', ['0', '1'])->comment('可否通关')->default('0');
            $table->enum('is_available', ['0', '1'])->comment('是否激活')->default('0');
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
