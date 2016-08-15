<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLogisticsSuppliersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('logistics_suppliers', function (Blueprint $table) {
            $table->string('credentials')->comment('企业证件')->after('remark');
            $table->integer('logistics_collection_info_id')->comment('收款信息')->after('remark');
            $table->string('driver_tel')->comment('司机电话')->after('remark');
            $table->string('driver')->comment('取件司机')->after('remark');
            $table->string('finance_tel')->comment('财务电话')->after('remark');
            $table->string('finance_qq')->comment('财务QQ')->after('remark');
            $table->string('finance_name')->comment('财务名称')->after('remark');
            $table->string('customer_service_tel')->comment('客服电话')->after('remark');
            $table->string('customer_service_qq')->comment('客服QQ')->after('remark');
            $table->string('customer_service_name')->comment('客服名称')->after('remark');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('logistics_suppliers', function (Blueprint $table) {
            $table->dropColumn('credentials');
            $table->dropColumn('logistics_collection_info_id');
            $table->dropColumn('driver_tel');
            $table->dropColumn('driver');
            $table->dropColumn('finance_tel');
            $table->dropColumn('finance_qq');
            $table->dropColumn('finance_name');
            $table->dropColumn('customer_service_tel');
            $table->dropColumn('customer_service_qq');
            $table->dropColumn('customer_service_name');
        });
    }
}
