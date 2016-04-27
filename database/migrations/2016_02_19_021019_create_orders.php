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
            $table->enum('status',
                [
                    'NEW',
                    'PREPARED',
                    'NEED',
                    'PACKED',
                    'SHIPPED',
                    'COMPLETE',
                    'CANCEL',
                    'ERROR'
                ])->default('NEW')->comment('订单状态');
            $table->enum('active',
                [
                    'NORMAL',
                    'VERIFY',
                    'CHARGEBACK',
                    'STOP',
                    'RESUME'
                ])->default('NORMAL')->comment('售后状态');
            $table->double('amount', 15, 2)->comment('总金额');
            $table->double('amount_product', 15, 2)->comment('产品金额');
            $table->double('amount_shipping', 15, 2)->comment('运费');
            $table->double('amount_coupon', 15, 2)->comment('折扣金额');
            $table->string('transaction_number')->comment('交易号');
            $table->enum('is_partial', [0, 1])->comment('是否分批发货')->default(0);
            $table->enum('by_hand', [0, 1])->comment('是否手工');
            $table->enum('is_affair', [0, 1])->comment('是否做账')->default(0);
            $table->string('affairer')->comment('做账人员')->nullable()->default(NULL);
            $table->string('customer_service')->comment('客服人员')->nullable()->default(NULL);
            $table->string('operator')->comment('运营人员')->nullable()->default(NULL);
            $table->enum('payment', ['GC', 'IDEAL', 'OC', 'PP', 'SOFORT'])->default('GC')->comment('支付方式');
            $table->enum('currency',
                [
                    'USD',
                    'GBP',
                    'EUR',
                    'NOK',
                    'CAD',
                    'AUD',
                    'CHF',
                    'SEK',
                    'PLN',
                    'RUB',
                    'MXN',
                    'DKK',
                    'SAR',
                    'TWD',
                    'JPY',
                    'HKD'
                ])->default('USD')->comment('币种');
            $table->double('rate', 15, 4)->comment('汇率');
            $table->string('ip')->comment('IP地址')->nullable()->default(NULL);
            $table->enum('address_confirm', [0, 1])->comment('地址验证')->default(1);
            $table->string('comment')->comment('备用字段')->nullable()->default(NULL);
            $table->string('comment1')->comment('红人/choies用')->nullable()->default(NULL);
            $table->string('remark')->comment('订单备注')->nullable()->default(NULL);
            $table->string('import_remark')->comment('导单备注')->nullable()->default(NULL);
            $table->enum('shipping', ['EXPRESS', 'PACKET'])->comment('种类');
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
            $table->string('refund')->comment('退款方式')->nullable()->default(NULL);
            $table->string('refund_currency')->comment('退款币种')->nullable()->default(NULL);
            $table->string('refund_account')->comment('客户账户')->nullable()->default(NULL);
            $table->string('refund_amount')->comment('退款金额')->nullable()->default(NULL);
            $table->string('cele_admin')->comment('红人单')->nullable()->default(NULL);
            $table->integer('priority')->comment('优先级')->nullable()->default(0);
            $table->integer('package_times')->comment('打包次数')->nullable()->default(0);
            $table->date('refund_time')->comment('退款时间')->nullable()->default(NULL);
            $table->date('payment_date')->comment('支付时间');
            $table->date('affair_time')->comment('做账时间')->nullable()->default(NULL);
            $table->date('create_time')->comment('渠道创建时间');
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
