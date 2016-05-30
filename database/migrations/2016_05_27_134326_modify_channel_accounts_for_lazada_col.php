<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyChannelAccountsForLazadaCol extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('channel_accounts', function (Blueprint $table) {
            //
            $table->string('lazada_account')->after('wish_proxy_address');
            $table->string('lazada_access_key')->after('wish_proxy_address');
            $table->string('lazada_user_id')->after('wish_proxy_address');
            $table->string('lazada_site')->after('wish_proxy_address');
            $table->string('lazada_currency_type')->after('wish_proxy_address');
            $table->string('lazada_currency_type_cn')->after('wish_proxy_address');
            $table->string('lazada_api_host')->after('wish_proxy_address');
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
            //
            $table->dropColumn(['lazada_account']);
            $table->dropColumn(['lazada_access_key']);
            $table->dropColumn(['lazada_user_id']);
            $table->dropColumn(['lazada_site']);
            $table->dropColumn(['lazada_currency_type']);
            $table->dropColumn(['lazada_currency_type_cn']);
            $table->dropColumn(['lazada_api_host']);


        });
    }
}
