<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddWishInfoToChannelAccountTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('channel_accounts', function (Blueprint $table) {

            $table->string('wish_publish_code')->comment('wish刊登代码')->after('aliexpress_access_token_date');
            $table->string('wish_client_id')->comment('WISH CLIENT_ID')->after('wish_publish_code');
            $table->string('wish_client_secret')->comment('WISH CLIENT_SECRET')->after('wish_client_id');
            $table->string('wish_redirect_uri')->comment('WISH API回调地址')->after('wish_client_secret');
            $table->string('wish_refresh_token')->comment('WISH REFRESH_TOKEN')->after('wish_redirect_uri');
            $table->string('wish_access_token')->comment('WISH ACCESS_TOKEN')->after('wish_refresh_token');
            $table->dateTime('wish_expiry_time')->comment('WISH ACCESS_TOKEN 过期时间')->after('wish_access_token');
            $table->string('wish_proxy_address')->comment('WISH 代理IP')->after('wish_expiry_time');
            $table->enum('wish_sku_resolve', ['1', '2'])->comment('WISH SKU 解析方式')->default('1')->after('wish_proxy_address');;
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('channel_accounts', function (Blueprint $table) {
            $table->dropColumn(['wish_publish_code']);
            $table->dropColumn(['wish_client_id']);
            $table->dropColumn(['wish_client_secret']);
            $table->dropColumn(['wish_redirect_uri']);
            $table->dropColumn(['wish_refresh_token']);
            $table->dropColumn(['wish_access_token']);
            $table->dropColumn(['wish_expiry_time']);
            $table->dropColumn(['wish_proxy_address']);
            $table->dropColumn(['wish_sku_resolve']);
        });
    }
}
