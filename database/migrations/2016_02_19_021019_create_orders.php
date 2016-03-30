<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('channel_id')->comment('渠道');
            $table->integer('channel_account_id')->comment('渠道账号');
            $table->string('ordernum')->comment('订单号');
            $table->string('channel_ordernum')->comment('渠道订单号');
            $table->string('email')->comment('邮箱');
            $table->integer('status')->comment('订单状态');
            $table->integer('active')->comment('售后状态');
            $table->double('amount', 15, 2)->comment('收款金额');
            $table->double('amount_product', 15, 2)->comment('产品金额');
            $table->double('amount_shipping', 15, 2)->comment('订单运费');
            $table->double('amount_coupon', 15, 2)->comment('折扣金额');
            $table->integer('is_partial')->comment('是否分批发货');
            $table->integer('by_hand')->comment('是否手工');
            $table->integer('is_affair')->comment('是否做账');
            $table->string('affairer')->comment('做账人员')->nullable()->default(NULL);
            $table->string('customer_service')->comment('客服人员')->nullable()->default(NULL);
            $table->string('operator')->comment('运营人员')->nullable()->default(NULL);
            $table->string('payment')->comment('支付方式');
            $table->string('currency')->comment('币种');
            $table->double('rate', 15, 4)->comment('汇率');
            $table->string('ip')->comment('IP地址');
            $table->integer('address_confirm')->comment('地址验证');
            $table->string('comment')->comment('备用字段')->nullable()->default(NULL);
            $table->string('comment1')->comment('红人/choies用')->nullable()->default(NULL);
            $table->string('remark')->comment('订单备注')->nullable()->default(NULL);
            $table->string('import_remark')->comment('导单备注')->nullable()->default(NULL);
            $table->string('shipping')->comment('种类');
            $table->string('shipping_firstname')->comment('发货名字');
            $table->string('shipping_lastname')->comment('发货姓氏');
            $table->string('shipping_address')->comment('发货地址');
            $table->string('shipping_address1')->comment('发货地址1')->nullable()->default(NULL);
            $table->string('shipping_city')->comment('发货城市');
            $table->string('shipping_state')->comment('发货省/州');
            $table->string('shipping_country')->comment('发货国家/地区');
            $table->string('shipping_zipcode')->comment('发货邮编');
            $table->string('shipping_phone')->comment('发货电话');
            $table->string('billing_firstname')->comment('账单名字')->nullable()->default(NULL);
            $table->string('billing_lastname')->comment('账单姓氏')->nullable()->default(NULL);
            $table->string('billing_address')->comment('账单地址')->nullable()->default(NULL);
            $table->string('billing_city')->comment('账单城市')->nullable()->default(NULL);
            $table->string('billing_state')->comment('账单省/州')->nullable()->default(NULL);
            $table->string('billing_country')->comment('账单国家/地区')->nullable()->default(NULL);
            $table->string('billing_zipcode')->comment('账单邮编')->nullable()->default(NULL);
            $table->string('billing_phone')->comment('账单电话')->nullable()->default(NULL);
            $table->date('payment_date')->comment('支付时间');
            $table->date('affair_time')->comment('做账时间');
            $table->date('create_time')->comment('定义时间');
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
        Schema::drop('orders');
    }
}
