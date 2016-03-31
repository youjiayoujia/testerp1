<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStockTakingForms extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_taking_forms', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('stock_taking_id')->comment('盘点表id')->default(0);
            $table->integer('stock_id')->comment('库存id')->default(0);
            $table->string('quantity')->comment('盘点数量')->default(NULL);
            $table->enum('stock_taking_status', ['more', 'equal', 'less'])->comment('盘点状态')->default('equal');
            $table->enum('stock_taking_yn', ['0', '1'])->comment('是否盘点更新')->default('0');
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
        Schema::drop('stock_taking_forms');
    }
}
