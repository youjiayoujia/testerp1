<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStockCarryOvers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_carry_overs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('date')->comment('月结时间')->default(NULL);
            $table->integer('warehouse_id')->comment('仓库')->default(NULL);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('stock_carry_overs');
    }
}
