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
            $table->timestamp('carry_over_time')->comment('结转时间')->default('0000-00-00 00:00:00');
            $table->integer('stock_id')->comment('stock ID')->default(0);
            $table->integer('begin_quantity')->comment('期初数量')->default(0);
            $table->decimal('begin_amount', 16, 4)->comment('期初金额')->default(0);
            $table->integer('over_quantity')->comment('期末数量')->default(0);
            $table->decimal('over_amount', 16, 4)->comment('期末金额')->default(0);
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
