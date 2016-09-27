<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCarryOverForms extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('carry_over_forms', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('parent_id')->comment('月结单id')->default(0);
            $table->integer('stock_id')->comment('stock ID')->default(0);
            $table->decimal('purchase_price', 8, 2)->comment('期初采购价')->default(0);
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
        Schema::drop('carry_over_forms');
    }
}
