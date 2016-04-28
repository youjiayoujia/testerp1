<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPurchaseItemsTable extends Migration
{
   /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchase_items', function (Blueprint $table) {
            $table->integer('stock_id')->nullable()->after('bar_code');
        });
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('purchase_items', function (Blueprint $table) {
            $table->dropColumn(['stock_id']);
        });
    }
}
