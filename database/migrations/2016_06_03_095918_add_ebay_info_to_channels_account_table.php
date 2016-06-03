<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEbayInfoToChannelsAccountTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('channel_accounts', function (Blueprint $table) {

            $table->string('ebay_developer_account')->comment('Ebay开发者账号')->after('wish_sku_resolve');
            $table->string('ebay_developer_devid')->comment('Ebay开发者账号devid')->after('ebay_developer_account');
            $table->string('ebay_developer_appid')->comment('Ebay开发者账号appid')->after('ebay_developer_devid');
            $table->string('ebay_developer_certid')->comment('Ebay开发者账号certid')->after('ebay_developer_appid');
            $table->text('ebay_token')->comment('EbayToken')->after('ebay_developer_certid');
            $table->string('ebay_eub_developer')->comment('EbayEub账号')->after('ebay_eub_developer');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('channel_accounts', function (Blueprint $table) {
            $table->dropColumn(['ebay_developer_account']);
            $table->dropColumn(['ebay_developer_devid']);
            $table->dropColumn(['ebay_developer_appid']);
            $table->dropColumn(['ebay_developer_certid']);
            $table->dropColumn(['ebay_token']);
            $table->dropColumn(['ebay_eub_developer']);
        });
    }
}
