<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePackages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('packages', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('channel_id')->comment('渠道ID');
            $table->integer('channel_account_id')->comment('渠道账号ID');
            $table->integer('order_id')->comment('订单ID');
            $table->integer('warehouse_id')->comment('仓库ID');
            $table->integer('logistics_id')->comment('物流方式ID');
            $table->integer('picklist_id')->comment('拣货单ID');
            $table->integer('assigner_id')->comment('负责人');
            $table->enum('type', ['SINGLE', 'SINGLEMULTI', 'MULTI',])->default('SINGLE')->comment('类型');
            $table->enum('status',
                [
                    'NEW',
                    'PROCESSING',
                    'PICKING',
                    'PICKED',
                    'PRINTED',
                    'PACKED',
                    'SHIPPED',
                    'ERROR'
                ])->default('NEW')->comment('状态');
            $table->decimal('cost', 10, 2)->comment('物流成本');
            $table->decimal('weight', 10, 2)->comment('重量');
            $table->decimal('length', 10, 2)->comment('长');
            $table->decimal('width', 10, 2)->comment('宽');
            $table->decimal('height', 10, 2)->comment('高');
            $table->string('tracking_no')->comment('追踪号');
            $table->string('tracking_link')->comment('追踪链接');
            $table->string('email')->comment('Email');
            $table->string('shipping_firstname')->comment('发货名字');
            $table->string('shipping_lastname')->comment('发货姓氏');
            $table->string('shipping_address')->comment('发货地址');
            $table->string('shipping_address1')->nullable()->comment('发货地址1');
            $table->string('shipping_city')->comment('发货城市');
            $table->string('shipping_state')->comment('发货省/州');
            $table->string('shipping_country')->comment('发货国家/地区');
            $table->string('shipping_zipcode')->comment('发货邮编');
            $table->string('shipping_phone')->comment('发货电话');
            $table->enum('is_auto', [0, 1])->default(0)->comment('是否自动发货');
            $table->text('remark')->comment('备注');
            $table->timestamp('logistic_assigned_at')->comment('物流分配时间');
            $table->timestamp('printed_at')->comment('打印面单时间');
            $table->timestamp('shipped_at')->comment('发货时间');
            $table->timestamp('delivered_at')->comment('交付时间');
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
        Schema::drop('packages');
    }
}
