<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnPicklistitems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('picklist_items', function (Blueprint $table) {
            $table->string('sku')->comment('sku')->default(NULL);
            $table->integer('packed_quantity')->comment('已包装数量')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('picklist_items', function (Blueprint $table) {
            $table->dropColumn('sku');
            $table->dropColumn('packed_quantity');
        });
    }
}
