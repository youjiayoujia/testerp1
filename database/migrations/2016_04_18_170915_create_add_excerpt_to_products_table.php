<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAddExcerptToProductsTable extends Migration
{
     /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
			$table->string('hs_code')->nullable()->after('data_edit_not_pass_remark');
			$table->string('unit')->nullable()->after('hs_code');
			$table->string('specification_model')->nullable()->after('unit');
			$table->integer('clearance_status')->nullable()->after('specification_model');
        });
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['hs_code']);
			$table->dropColumn(['unit']);
			$table->dropColumn(['specification_model']);
			$table->dropColumn(['clearance_status']);
        });
    }
}
