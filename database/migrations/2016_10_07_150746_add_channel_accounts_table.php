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
            $table->string('joom_publish_code')->default('')->comment('joom_code')->after('lazada_api_host');
            $table->string('joom_client_id')->default('')->comment('joom_client_id')->after('lazada_api_host');
            $table->string('joom_client_secret')->default('')->comment('joom_document')->after('lazada_api_host');
            $table->string('joom_redirect_uri')->default('')->comment('joomrui')->after('lazada_api_host');
            $table->string('joom_refresh_token',355)->default('')->comment('joom_refresh_token')->after('lazada_api_host');
            $table->string('joom_access_token',355)->default('')->comment('joom_document_token')->after('lazada_api_host');
            $table->string('joom_expiry_time')->default('')->comment('joom_expiry_time')->after('lazada_api_host');
            $table->string('joom_proxy_address')->default('')->comment('address')->after('lazada_api_host');
            $table->enum('joom_sku_resolve', ['1', '2'])->comment('sku_resolve')->after('lazada_api_host');
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
            $table->dropColumn(['joom_publish_code']);
            $table->dropColumn(['joom_client_id']);
            $table->dropColumn(['joom_client_secret']);
            $table->dropColumn(['joom_redirect_uri']);
            $table->dropColumn(['joom_refresh_token']);
            $table->dropColumn(['joom_access_token']);
            $table->dropColumn(['joom_expiry_time']);
            $table->dropColumn(['joom_proxy_address']);
            $table->dropColumn(['joom_sku_resolve']);
        });
    }
}
