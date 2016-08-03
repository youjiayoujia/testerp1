<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLogisticsesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('logisticses', function (Blueprint $table) {
            $table->string('driver')->comment('驱动名')->nullable()->default(NULL)->after('url');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('logisticses', function (Blueprint $table) {
            $table->dropColumn('driver');
        });
    }
}
