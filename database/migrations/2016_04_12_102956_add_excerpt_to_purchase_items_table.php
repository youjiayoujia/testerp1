<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddExcerptToPurchaseItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchase_items', function (Blueprint $table) {
            $table->date('start_buying_time')->nullable()->after('arrival_time');
			$table->string('bar_code')->nullable()->after('arrival_time');
			$table->int('storage_qty')->nullable()->after('arrival_time');
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
            $table->dropColumn(['start_buying_time']);
			$table->dropColumn(['bar_code']);
			$table->dropColumn(['storage_qty']);
        });
    }
}
