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
            $table->text('message_secret')->nullable()->after('amazon_accesskey_secret');
            $table->text('message_token')->nullable()->after('amazon_accesskey_secret');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}
