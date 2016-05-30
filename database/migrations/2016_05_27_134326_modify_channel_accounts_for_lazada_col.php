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
            $table->string('lazada_account')->after('amazon_accesskey_secret');
            $table->string('lazada_access_key')->after('lazada_account');
            $table->string('lazada_user_id')->after('lazada_access_key');
            $table->string('lazada_site')->after('lazada_user_id');
            $table->string('lazada_currency_type')->after('lazada_site');
            $table->string('lazada_currency_type_cn')->after('lazada_currency_type');
            $table->string('lazada_api_host')->after('lazada_currency_type_cn');
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
