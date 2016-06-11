<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLogisticsesRuleColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('logistics_rules', function (Blueprint $table) {
            $table->dropColumn('country');
            $table->decimal('order_amount_from', '7', '2')->comment('起始订单金额')->default(0);
            $table->decimal('order_amount_to', '7', '2')->comment('结束订单金额')->default(0);
            $table->string('name')->comment('分配名')->default(NULL);
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
            //
        });
    }
}
