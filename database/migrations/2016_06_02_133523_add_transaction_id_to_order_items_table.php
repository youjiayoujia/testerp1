<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTransactionIdToOrderItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('order_items', function (Blueprint $table) {
            $table->string('transaction_id')->comment('ebay transaction_id')->nullable()->default(NULL)->after('channel_order_id');
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
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn(['transaction_id']);
        });
    }
}
