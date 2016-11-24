<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIsReviewReviewTypeToOrder extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->enum('review_type', ['REQUIRE', 'PROFIT', 'MESSAGE', 'BLACK', 'WEIGHT', 'ITEM'])
                ->comment('审核类型')->after('platform');
            $table->enum('is_reviewed', ['0', '1'])
                ->comment('是否审核')->default('1')->after('review_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('review_type');
            $table->dropColumn('is_reviewed');
        });
    }
}
