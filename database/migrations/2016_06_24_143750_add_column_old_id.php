<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnOldId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_suppliers', function (Blueprint $table) {
            $table->integer('old_id')->comment('sm_supplier_id')->after('id')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_suppliers', function (Blueprint $table) {
            $table->dropColumn('old_id');
        });
    }
}
