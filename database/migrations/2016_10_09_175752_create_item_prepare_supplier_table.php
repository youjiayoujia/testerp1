<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemPrepareSupplierTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_prepare_suppliers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('item_id')->comment('sku_id');
            $table->integer('supplier_id')->comment('sku_supplier');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('item_prepare_suppliers');
    }
}
