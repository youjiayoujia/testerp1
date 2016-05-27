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
                    '1',
                    '2'
                ])->comment('退款方式');
            $table->enum('type',
                [
                    'FULL',
                    'PARTIAL'
                ])->comment('退款类型');
            $table->enum('reason',
                [
                    '1', '2', '3', '4', '5', '6', '7', '8',
                    '9', '10', '11', '12', '13', '14', '15'
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
