<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnBankAccount extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('logistics_suppliers', function (Blueprint $table) {
            $table->string('bank')->comment('银行')->default(NULL);
            $table->string('card_number')->comment('卡号')->default(NULL);
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
            $table->dropColumn('bank');
            $table->dropColumn('card_number');
        });
    }
}
