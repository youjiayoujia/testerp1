<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderRefunds extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_refunds', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order_id')->comment('订单ID');
            $table->double('refund_amount', 15, 2)->comment('退款金额');
            $table->double('price', 15, 2)->comment('确认金额');
            $table->string('refund_currency')->comment('退款币种');
            $table->enum('refund',
                [
                    'Paypal',
                    '销售平台'
                ])->comment('退款方式');
            $table->enum('type',
                [
                    '全部退款',
                    '部分退款'
                ])->comment('退款类型');
            $table->enum('reason',
                [
                    '[没发货] 客户取消',
                    '[没发货] 缺货中国仓',
                    '[没发货] 亏本+物品被删',
                    '[没发货] 付款审查/资金冻结',
                    '[中国发] 物流问题',
                    '[中国发] 没出国退回',
                    '[中国发] 关税',
                    '[海外仓] 缺货海外仓',
                    '[海外仓] 物流问题',
                    '质量问题(尺码色差不能用不满意)',
                    '运输损坏',
                    '发错货(中国仓)',
                    '发错货(海外仓)',
                    '广告错/SKU错/客户错',
                    '漏配件'
                ])->comment('退款原因');
            $table->text('memo')->comment('memo')->nullable()->default(NULL);
            $table->text('detail_reason')->comment('详细原因')->nullable()->default(NULL);
            $table->string('image')->comment('截图')->nullable()->default(NULL);
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
        Schema::drop('order_refunds');
    }
}
