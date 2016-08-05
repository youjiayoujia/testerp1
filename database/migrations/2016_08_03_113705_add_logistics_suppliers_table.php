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
            $table->string('password')->comment('密码')->nullable()->default(NULL)->after('customer_id');
            $table->string('url')->comment('URL')->nullable()->default(NULL)->after('customer_id');
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
            $table->dropColumn('password');
            $table->dropColumn('url');
        });
    }
}
