<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddChannelAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('channel_accounts', function (Blueprint $table) {

            $table->string('aliexpress_member_id')->comment(' 速卖通开发者账号')->after('amazon_accesskey_id');
            $table->string('aliexpress_appkey')->comment('速卖通appkey')->after('aliexpress_member_id');
            $table->string('aliexpress_appsecret')->comment('速卖通appsecret')->after('aliexpress_appkey');
            $table->string('aliexpress_returnurl')->comment('速卖通回调地址')->after('aliexpress_appsecret');
            $table->string('aliexpress_refresh_token')->comment('速卖通refresh_token(半年有效期)')->after('aliexpress_returnurl');
            $table->string('aliexpress_access_token')->comment('速卖通access_token(10小时有效期)')->after('aliexpress_refresh_token');
            $table->dateTime('aliexpress_access_token_date')->comment('速卖通access_token(10小时有效期)')->after('aliexpress_access_token');
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
            $table->dropColumn(['aliexpress_member_id']);
            $table->dropColumn(['aliexpress_appkey']);
            $table->dropColumn(['aliexpress_appsecret']);
            $table->dropColumn(['aliexpress_returnurl']);
            $table->dropColumn(['aliexpress_refresh_token']);
            $table->dropColumn(['aliexpress_access_token']);
            $table->dropColumn(['aliexpress_access_token_date']);
        });
    }
}
