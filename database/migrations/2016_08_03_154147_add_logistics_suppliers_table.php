<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLogisticsSuppliersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('logistics_suppliers', function (Blueprint $table) {
            $table->string('customer_id')->comment('客户ID')->nullable()->default(NULL)->after('name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('logistics_suppliers', function (Blueprint $table) {
            $table->dropColumn('customer_id');
        });
    }
}
