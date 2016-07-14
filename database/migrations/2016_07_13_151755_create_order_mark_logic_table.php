<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderMarkLogicTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_mark_logic', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('channel_id')->comment('渠道id');
            $table->string('order_status')->comment('订单状态');
            $table->integer('order_create')->comment('订单创建N小时后');
            $table->integer('order_pay')->comment('订单付款N小时');
            $table->enum('assign_shipping_logistics', ['1','2'])->comment('  1 根据平台承运商标记发货 2 手动指定承运商标记发货')->default('1');
            $table->string('shipping_logistics_name')->comment('指定的承运商');
            $table->enum('is_upload', ['1','2'])->comment('  1 按物流渠道设置  2  标记发货但不上传跟踪号 ')->default('1');
            $table->integer('user_id')->comment('设置人员');
            $table->integer('priority')->comment('规则优先级');
            $table->enum('wish_upload_tracking_num', ['0','1'])->comment('  0 否  1 是 ')->default('0');
            $table->enum('is_use', ['0','1'])->comment('  1 启用  0 不启用 ')->default('1');
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
        Schema::drop('order_mark_logic');
    }
}
