<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddProductSupplierTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_suppliers', function (Blueprint $table) {
            $table->integer('purchase_time')->nullable()->after('created_by');
			$table->string('bank_account')->nullable()->after('purchase_time');
			$table->string('bank_code')->nullable()->after('bank_account');
			$table->enum('pay_type',
                [
                    'ONLINE',
                    'BANK_PAY',
                    'CASH_PAY',
                    'OTHER_PAY'
                ])->default('ONLINE')->comment('状态')->after('bank_code');
			$table->string('qualifications')->nullable()->after('pay_type');
			$table->string('examine_status')->nullable()->default(0)->after('qualifications');		
        });
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_suppliers', function (Blueprint $table) {
            $table->dropColumn(['purchase_time']);
			$table->dropColumn(['bank_account']);
			$table->dropColumn(['bank_code']);
			$table->dropColumn(['pay_type']);
			$table->dropColumn(['qualifications']);
			$table->dropColumn(['examine_status']);
        });
    }
}
