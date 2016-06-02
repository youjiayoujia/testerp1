<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->enum('withdraw',
                [
                    '1', '2', '3', '4', '5', '6', '7', '8', '9', '10'
                ])->comment('撤单原因')->nullable()->default(NULL);
            $table->double('platform', 15, 2)->comment('平台费')->nullable()->default(0);
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
            $table->dropColumn(['withdraw']);
            $table->dropColumn(['platform']);
        });
    }
}