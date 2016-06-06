<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsForCdiscountForOrderInChannelAccountsTable extends Migration
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
            $table->string('cd_currency_type')->nullable()->after('ebay_eub_developer');
            $table->string('cd_currency_type_cn')->nullable()->after('ebay_eub_developer');

            $table->string('cd_account')->nullable()->after('ebay_eub_developer');
            $table->string('cd_token_id')->nullable()->after('ebay_eub_developer');
            $table->string('cd_pw')->nullable()->after('ebay_eub_developer');
            $table->string('cd_sales_account')->nullable()->after('ebay_eub_developer');
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
        });
    }
}
