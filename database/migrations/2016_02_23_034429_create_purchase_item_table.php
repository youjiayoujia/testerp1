<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchaseItemTable extends Migration
{
     /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_items', function (Blueprint $table) {
            $table->increments('id');
			$table->integer('type')->comment('采购类型')->nullable()->default(NULL);
			$table->integer('status')->comment('采购条目的状态')->default(0);
			$table->integer('storageStatus')->comment('入库状态')->default(0);
			$table->integer('costExamineStatus')->comment('采购成本审核状态')->default(0);
            $table->integer('order_item_id')->comment('订单itemID')->nullable()->default(NULL);
			$table->string('sku')->comment('sku')->default(NULL);
			$table->integer('supplier_id')->comment('供应商ID')->default(NULL);
			$table->integer('purchase_num')->comment('需要采购数量')->default(NULL);
			$table->integer('arrival_num')->comment('已到货数量')->default(0);
			$table->integer('lack_num')->comment('仍需采购数量')->default(NULL);	
			$table->integer('warehouse_id')->comment('采购条目所属仓库')->default(NULL);
			$table->integer('purchase_order_id')->comment('所属采购单ID')->default(NULL);
			$table->double('postage', 15, 8)->comment('物流费用')->default(NULL);
			$table->string('post_coding')->comment('物流单号')->default(0);
			$table->double('purchase_cost', 15, 8)->comment('采购价格')->default(NULL);
			$table->integer('active')->comment('异常种类')->default(0);	
			$table->integer('active_status')->comment('异常处理状态')->default(0);
			$table->string('remark')->comment('异常备注')->default(NULL);
			$table->date('arrival_time')->comment('到货时间')->default(NULL);
			$table->integer('user_id')->comment('创建该采购条目者')->default(NULL);
			$table->integer('update_userid')->comment('处理者ID')->default(NULL);
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
