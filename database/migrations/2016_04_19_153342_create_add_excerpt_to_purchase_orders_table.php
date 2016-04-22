<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAddExcerptToPurchaseOrdersTable extends Migration
{
   /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchase_orders', function (Blueprint $table) {
            $table->integer('assigner')->nullable()->after('start_buying_time');
        });
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('purchase_orders', function (Blueprint $table) {
            $table->dropColumn(['assigner']);
        });
    }
}
