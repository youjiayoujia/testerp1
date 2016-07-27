<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('by_id')->comment('买家ID')->nullable()->default(NULL)->after('channel_ordernum');
            $table->string('withdraw_reason')->comment('撤单原因(自述)')->nullable()->default(NULL)->after('withdraw');
            $table->string('email')->comment('邮箱')->nullable()->default(NULL)->after('channel_ordernum');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('by_id');
            $table->dropColumn('withdraw_reason');
            $table->dropColumn('email');
        });
    }
}
