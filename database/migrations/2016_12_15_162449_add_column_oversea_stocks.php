<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnOverseaStocks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stocks', function (Blueprint $table) {
            $table->string('oversea_sku')->comment('海外仓sku')->default(NULL);
            $table->decimal('oversea_cost',6,3)->comment('海外仓产品单价')->default(0.0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stocks', function (Blueprint $table) {
            $table->dropColumn('oversea_sku');
            $table->dropColumn('oversea_cost');
        });
    }
}
