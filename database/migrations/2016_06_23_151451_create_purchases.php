<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchases extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchases', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('item_id')->comment('item_id')->default(0);
            $table->string('sku')->comment('sku')->default(NULL);
            $table->string('c_name')->comment('中文名')->default(NULL);
            $table->integer('all_quantity')->comment('实库存')->default(0);
            $table->integer('available_quantity')->comment('可用库存')->default(0);
            $table->integer('zaitu_num')->comment('在途数量')->default(0);
            $table->integer('seven_sales')->comment('7天销量')->default(0);
            $table->integer('fourteen_sales')->comment('14天销量')->default(0);
            $table->integer('thirty_sales')->comment('30天销量')->default(0);
            $table->string('thrend')->comment('趋势')->default(NULL);
            $table->integer('need_purchase_num')->comment('建议采购数量')->default(0);
            $table->decimal('refund_rate',7,2)->comment('退款率')->default(0);
            $table->decimal('profit',7,2)->comment('利润率')->default(0);
            $table->string('status')->comment('状态')->default(NULL);
            $table->enum('require_create',['0','1'])->comment('是否需要创建采购单')->default('0');
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
        Schema::drop('purchases');
    }
}
