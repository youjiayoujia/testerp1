<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLogisticsRulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('logistics_rules', function (Blueprint $table) {
            $table->enum('transport_section', ['0', '1'])->comment('运输区间')->default('0');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('logistics_rules', function (Blueprint $table) {
            $table->dropColumn('transport_section');
        });
    }
}
