<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchaseOrderConfirm extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_order_confirms', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('po_id')->comment('采购单号')->default(NULL);
            $table->integer('status')->comment('核销状态(同采购单)')->default(NULL);
            $table->integer('real_money')->comment('实际核销金额')->default(0);
            $table->integer('no_delivery_money')->comment('未到货金额')->default(0);
            $table->integer('reason')->comment('核销原因')->default(0);
            $table->integer('credential')->comment('退款凭证')->default(0);
            $table->integer('po_user')->comment('采购人')->default(0);
            $table->integer('refund_time')->comment('退款时间')->default(0);
            $table->integer('create_user')->comment('上传人')->default(0);
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
        Schema::drop('purchase_order_confirms');
    }
}
