<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('catalog_id')->nullable();
            $table->string('product_id')->comment('产品id')->nullable()->default(0);
            $table->string('sku')->comment('sku')->nullable()->default(0);
            $table->string('name')->nullable();
            $table->string('c_name')->nullable();
            $table->string('weight')->comment('重量')->nullable()->default(0);
            $table->string('inventory')->comment('库存')->nullable()->default(NULL);
            $table->integer('warehouse_id')->comment('仓库')->default(0);
            $table->string('alias_name')->nullable();
            $table->string('alias_cname')->nullable();
            $table->integer('supplier_id')->nullable();
            $table->string('supplier_sku')->nullable();
            $table->string('second_supplier_id')->nullable();
            $table->string('supplier_info')->nullable();
            $table->string('purchase_url')->nullable();
            $table->decimal('purchase_price', 7, 2)->nullable();
            $table->decimal('purchase_carriage', 5, 2)->nullable();
            $table->float('cost')->nullable()->after('purchase_carriage');
            $table->string('product_size')->nullable();
            $table->string('package_size')->nullable();
            $table->string('carriage_limit')->nullable();
            $table->string('package_limit')->nullable();
            $table->tinyInteger('status')->nullable();
            $table->tinyInteger('is_sale')->comment('是否可售')->nullable()->default(1);
            $table->string('remark')->nullable();
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
        Schema::drop('items');
    }
}
