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
            $table->integer('priority')->comment('优先级')->after('id');
            $table->string('driver')->comment('驱动名')->nullable()->default(NULL)->after('url');
            $table->string('logistics_code')->comment('物流编码')->nullable()->default(NULL)->after('limit');
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
            $table->dropColumn('priority');
            $table->dropColumn('driver');
            $table->dropColumn('logistics_code');
        });
    }
}
