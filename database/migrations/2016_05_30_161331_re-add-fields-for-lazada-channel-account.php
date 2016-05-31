<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ReAddFieldsForLazadaChannelAccount extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->down();

        Schema::table('channel_accounts', function (Blueprint $table) {
            //
            $table->string('lazada_access_key')->nullable()->default(NULL)->after('amazon_accesskey_secret');
            $table->string('lazada_user_id')->nullable()->default(NULL)->after('lazada_access_key');
            $table->string('lazada_site')->nullable()->default(NULL)->after('lazada_user_id');
            $table->string('lazada_currency_type')->nullable()->default(NULL)->after('lazada_site');
            $table->string('lazada_currency_type_cn')->nullable()->default(NULL)->after('lazada_currency_type');
            $table->string('lazada_api_host')->nullable()->default(NULL)->after('lazada_currency_type_cn');
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

            $table->dropColumn('lazada_account');

            $table->dropColumn('lazada_access_key');
            $table->dropColumn('lazada_user_id');
            $table->dropColumn('lazada_site');
            $table->dropColumn('lazada_currency_type');
            $table->dropColumn('lazada_currency_type_cn');
            $table->dropColumn('lazada_api_host');

        });
    }
}
