<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchaseOrderTable extends Migration
{
     /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->increments('id');
			$table->integer('type')->comment('采购类型')->nullable()->default(NULL);
			$table->integer('status')->comment('采购单的状态')->default(0);
			$table->integer('examineStatus')->comment('采购单的审核状态')->default(0);
			$table->integer('costExamineStatus')->comment('采购单价格审核状态')->default(0);
			$table->integer('close_status')->comment('结算状态')->default(0);
			$table->integer('supplier_id')->comment('供应商ID')->default(NULL);
			$table->integer('user_id')->comment('创建该采购单者')->nullable()->default(NULL);
			$table->integer('purchase_userid')->comment('采购者')->nullable()->default(NULL);
			$table->integer('update_userid')->comment('处理者ID')->nullable()->default(NULL);
			$table->integer('warehouse_id')->comment('采购条目所属仓库')->default(NULL);
			$table->double('total_postage', 15, 8)->comment('物流费用')->nullable()->default(NULL);
			$table->string('post_coding')->comment('物流单号')->nullable()->default(NULL);
			$table->double('total_purchase_cost', 15, 8)->comment('采购价格')->nullable()->default(NULL);
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
        Schema::drop('purchase_items');
    }
}
