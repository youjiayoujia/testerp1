<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterChannel extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('channels', function (Blueprint $table) {
            $table->enum('flat_rate', ['channel', 'catalog'])->comment('固定费用')->default('catalog');
            $table->enum('rate', ['channel', 'catalog'])->comment('费率')->default('catalog');
            $table->decimal('flat_rate_value', 6, 3)->comment('固定费用值')->default(0);
            $table->decimal('rate_value', 7, 6)->comment('费率值')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('channels', function (Blueprint $table) {
            $table->dropColumn('flat_rate');
            $table->dropColumn('rate');
            $table->dropColumn('flat_rate_value');
            $table->dropColumn('rate_value');
        });
    }
}
