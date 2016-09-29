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
            $table->integer('sync_days')->comment('订单抓取时长');
            $table->integer('sync_pages')->comment('订单每页抓取数量');
            $table->string('domain')->comment('账号对应域名');
            $table->string('image_domain')->comment('产品图片域名');
            $table->string('service_email')->comment('客服邮箱地址');
            $table->integer('operator_id')->comment('默认运营人员');
            $table->integer('customer_service_id')->comment('默认客服人员');
            $table->enum('is_clearance', ['0', '1'])->comment('可否通关')->default('0');
            $table->enum('is_available', ['0', '1'])->comment('是否激活')->default('0');
            //AMAZON API
            $table->string('amazon_api_url')->comment('AWS Service Url');
            $table->string('amazon_marketplace_id')->comment('AWS MarketplaceId');
            $table->string('amazon_seller_id')->comment('AWS SellerId');
            $table->string('amazon_accesskey_id')->comment('AWS AWSAccessKeyId');
            $table->string('amazon_accesskey_secret')->comment('AWS Accesskey Secret');
            //WISH API
            $table->string('wish_publish_code')->comment('wish刊登代码');
            $table->string('wish_client_id')->comment('WISH CLIENT_ID');
            $table->string('wish_client_secret')->comment('WISH CLIENT_SECRET');
            $table->string('wish_redirect_uri')->comment('WISH API回调地址');
            $table->string('wish_refresh_token')->comment('WISH REFRESH_TOKEN');
            $table->string('wish_access_token')->comment('WISH ACCESS_TOKEN');
            $table->dateTime('wish_expiry_time')->comment('WISH ACCESS_TOKEN 过期时间');
            $table->string('wish_proxy_address')->comment('WISH 代理IP');
            $table->enum('wish_sku_resolve', ['1', '2'])->comment('WISH SKU 解析方式')->default('1');
            //ALIEXPRESS API
            $table->string('aliexpress_member_id')->comment(' 速卖通开发者账号');
            $table->string('aliexpress_appkey')->comment('速卖通appkey');
            $table->string('aliexpress_appsecret')->comment('速卖通appsecret');
            $table->string('aliexpress_returnurl')->comment('速卖通回调地址');
            $table->string('aliexpress_refresh_token')->comment('速卖通refresh_token(半年有效期)');
            $table->string('aliexpress_access_token')->comment('速卖通access_token(10小时有效期)');
            $table->dateTime('aliexpress_access_token_date')->comment('速卖通access_token(10小时有效期)');
            //EBAY API
            $table->string('ebay_developer_account')->comment('Ebay开发者账号');
            $table->string('ebay_developer_devid')->comment('Ebay开发者账号devid');
            $table->string('ebay_developer_appid')->comment('Ebay开发者账号appid');
            $table->string('ebay_developer_certid')->comment('Ebay开发者账号certid');
            $table->text('ebay_token')->comment('EbayToken');
            $table->string('ebay_eub_developer')->comment('EbayEub账号');
            //LAZADA API
            $table->string('lazada_access_key')->nullable()->default(null);
            $table->string('lazada_user_id')->nullable()->default(null);
            $table->string('lazada_site')->nullable()->default(null);
            $table->string('lazada_currency_type')->nullable()->default(null);
            $table->string('lazada_currency_type_cn')->nullable()->default(null);
            $table->string('lazada_api_host')->nullable()->default(null);
            //CD API
            $table->string('cd_currency_type')->nullable();
            $table->string('cd_currency_type_cn')->nullable();
            $table->string('cd_account')->nullable();
            $table->string('cd_token_id')->nullable();
            $table->string('cd_pw')->nullable();
            $table->string('cd_sales_account')->nullable();
            $table->integer('cd_expires_in')->nullable();
            //渠道地域id 例如：亚马逊美国
            $table->integer('catalog_rates_channel_id')->nullable();
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
