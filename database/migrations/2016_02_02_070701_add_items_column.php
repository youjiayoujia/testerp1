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
            $table->string('name')->nullable()->after('sku');
            $table->string('c_name')->nullable()->after('sku')->after('name');
            $table->string('alias_name')->nullable()->after('c_name');
            $table->string('alias_cname')->nullable()->after('alias_name');
            $table->integer('catalog_id')->nullable()->after('alias_cname');
            $table->integer('supplier_id')->nullable()->after('catalog_id');
            $table->string('supplier_sku')->nullable()->after('supplier_id');
            $table->string('second_supplier_id')->nullable()->after('supplier_sku');
            $table->string('supplier_info')->nullable()->after('second_supplier_id');
            $table->string('purchase_url')->nullable()->after('supplier_info');
            $table->decimal('purchase_price',7,2)->nullable()->after('purchase_url');
            $table->decimal('purchase_carriage',5,2)->nullable()->after('purchase_price');
            $table->string('product_size')->nullable()->after('purchase_carriage');
            $table->string('package_size')->nullable()->after('product_size');
            $table->string('carriage_limit')->nullable()->after('package_size');
            $table->string('package_limit')->nullable()->after('carriage_limit');
            $table->tinyInteger('status')->nullable()->after('package_limit');
            $table->string('remark')->nullable()->after('status');
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
