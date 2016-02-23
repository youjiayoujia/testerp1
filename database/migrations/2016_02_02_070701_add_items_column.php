<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddItemsColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('items', function(Blueprint $table)
        {
            $table->string('name')->nullable();
            $table->string('c_name')->nullable();
            $table->string('alias_name')->nullable();
            $table->string('alias_cname')->nullable();
            $table->integer('catalog_id')->nullable();
            $table->integer('supplier_id')->nullable();
            $table->string('supplier_sku')->nullable();
            $table->string('second_supplier_id')->nullable();
            $table->string('supplier_info')->nullable();
            $table->string('purchase_url')->nullable();
            $table->decimal('purchase_price',7,2)->nullable();
            $table->decimal('purchase_carriage',5,2)->nullable();
            $table->string('product_size')->nullable();
            $table->string('package_size')->nullable();
            $table->string('carriage_limit')->nullable();
            $table->string('package_limit')->nullable();
            $table->tinyInteger('status')->nullable();
            $table->string('remark')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $table->dropColumn('name');
        $table->dropColumn('c_name');
        $table->dropColumn('alias_name');
        $table->dropColumn('alias_cname');
        $table->dropColumn('catalog_id');
        $table->dropColumn('supplier_id');
        $table->dropColumn('supplier_sku');
        $table->dropColumn('second_supplier_id');
        $table->dropColumn('supplier_info');
        $table->dropColumn('purchase_url');
        $table->dropColumn('purchase_price');
        $table->dropColumn('purchase_carriage');
        $table->dropColumn('product_size');
        $table->dropColumn('package_size');
        $table->dropColumn('carriage_limit');
        $table->dropColumn('package_limit');
        $table->dropColumn('status');
        $table->dropColumn('remark');
    }
}
