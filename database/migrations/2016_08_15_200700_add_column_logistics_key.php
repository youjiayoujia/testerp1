<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnLogisticsKey extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('logistics_channel_names', function (Blueprint $table) {
            $table->string('logistics_key')->comment('物流值')->default(NULL);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('logistics_channel_names', function (Blueprint $table) {
            $table->dropColumn('logistics_key');
        });
    }
}
