<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('parts')->comment('配件')->default(NULL);
            $table->string('declared_cn')->comment('申报中文名')->default(NULL);
            $table->string('declared_en')->comment('申报英文名')->default(NULL);
            $table->decimal('declared_value', 5, 2)->comment('申报价格')->default(0);
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
            $table->dropColumn('parts');
            $table->dropColumn('declared_cn');
            $table->dropColumn('declared_en');
            $table->dropColumn('declared_value');
        });
    }
}
