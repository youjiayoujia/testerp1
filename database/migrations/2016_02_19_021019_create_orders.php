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
            $table->enum('status',
                [
                    'UNPAID',
                    'PAID',
                    'PREPARED',
                    'PARTIAL',
                    'NEED',
                    'PACKED',
                    'SHIPPED',
                    'COMPLETE',
                    'CANCEL',
                    'REVIEW'
                ])->default('PAID')->comment('订单状态');
            $table->enum('active',
                [
                    'NORMAL',
                    'VERIFY',
                    'CHARGEBACK',
                    'STOP',
                    'RESUME'
                ])->default('NORMAL')->comment('售后状态');
            $table->double('amount', 15, 2)->comment('总金额');
            $table->string('gross_margin')->comment('预测毛利率')->nullable()->defalut(NULL);
            $table->decimal('profit_rate', 7, 2)->comment('利润率')->nullable()->defalut(NULL);
            $table->double('amount_product', 15, 2)->comment('产品金额');
            $table->double('amount_shipping', 15, 2)->comment('运费');
            $table->double('amount_coupon', 15, 2)->comment('折扣金额');
            $table->string('transaction_number')->comment('交易号');
            $table->string('customer_service')->comment('客服人员')->nullable()->default(NULL);
            $table->string('operator')->comment('运营人员')->nullable()->default(NULL);
            $table->string('payment')->comment('支付方式')->default(NULL);
            $table->string('currency')->comment('币种');
            $table->double('rate', 15, 4)->comment('汇率');
            $table->enum('address_confirm', [0, 1])->comment('地址验证')->default(1);
            $table->string('shipping')->comment('物流方式')->nullable()->default(NULL);
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
            $table->enum('withdraw',
                [
                    '1', '2', '3', '4', '5', '6', '7', '8', '9', '10'
                ])->comment('撤单原因')->nullable()->default(NULL);
            $table->string('cele_admin')->comment('红人单')->nullable()->default(NULL);
            $table->integer('priority')->comment('优先级')->nullable()->default(0);
            $table->integer('package_times')->comment('打包次数')->nullable()->default(0);
            $table->integer('split_times')->comment('拆分次数')->nullable()->default(0);
            $table->integer('split_quantity')->comment('被拆分数量')->nullable()->default(0);
            $table->string('fulfill_by')->comment('处理方')->nullable()->default(NULL);
            $table->enum('blacklist', ['0', '1'])->comment('黑名单订单')->nullable()->default('1');
            $table->double('platform', 15, 2)->comment('平台费')->nullable()->default(0);
            $table->string('aliexpress_loginId')->comment('aliexpress买家的账号id')->nullable()->default(null);
            $table->date('payment_date')->comment('支付时间');
            $table->timestamp('create_time')->comment('渠道创建时间');
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
