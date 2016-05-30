<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPurchaseOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchase_orders', function (Blueprint $table) {
            $table->enum('print_status',['0','1'])->default('0')->comment('打印状态')->after('assigner');
			$table->integer('print_num')->nullable()->default(0)->comment('打印次数')->after('print_status');
			$table->enum('write_off',['0','1'])->default(0)->comment('核销状态')->after('print_num');
			$table->enum('pay_type',
                [
                    'ONLINE',
                    'BANK_PAY',
                    'CASH_PAY',
                    'OTHER_PAY'
                ])->default('ONLINE')->comment('付款方式')->after('write_off');	
        });
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('purchase_orders', function (Blueprint $table) {
            $table->dropColumn(['print_status']);
			$table->dropColumn(['print_num']);
			$table->dropColumn(['write_off']);
			$table->dropColumn(['pay_type']);
        });
    }
}
