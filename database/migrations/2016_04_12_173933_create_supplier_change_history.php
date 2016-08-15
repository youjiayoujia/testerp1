<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSupplierChangeHistory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('supplier_change_historys', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('supplier_id')->comment('供货商id')->default(0);
            $table->integer('from')->comment('原始采购员')->default(0);
            $table->integer('to')->comment('变更后采购员')->default(0);
            $table->integer('adjust_by')->comment('变更人')->default(0);
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
        Schema::drop('supplier_change_historys');
    }
}
