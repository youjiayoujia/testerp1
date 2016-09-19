<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReports extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('oversea_reports', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('account_id')->comment('帐号')->default(0);
            $table->string('plan_id')->comment('plan  ID')->default(0);
            $table->string('shipment_id')->comment('shipment id')->default(0);
            $table->string('reference_id')->comment('reference_id')->default(0);
            $table->string('shipment_name')->comment('shipment name')->default(NULL);
            $table->enum('status', ['NEW', 'PASS', 'FAIL', 'PICKING', 'PACKING', 'PACKED', 'SHIPPED'])->comment('状态')->default('NEW');
            $table->enum('print_status', ['UNPRINT', 'PRINTED'])->comment('打印状态')->default('UNPRINT');
            
            $table->string('inStock_status')->comment('入库状态')->default(NULL);
            $table->string('shipping_firstname')->comment('发货名字')->default(NULL);
            $table->string('shipping_lastname')->comment('发货姓氏')->default(NULL);
            $table->string('shipping_address')->comment('发货地址')->default(NULL);
            $table->string('shipping_city')->comment('发货城市')->default(NULL);
            $table->string('shipping_state')->comment('发货省/州')->default(NULL);
            $table->string('shipping_country')->comment('发货国家/地区')->default(NULL);
            $table->string('shipping_zipcode')->comment('发货邮编')->default(NULL);
            $table->string('shipping_phone')->comment('发货电话')->default(NULL);

            $table->integer('quantity')->comment('箱数')->default(NULL);
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
        Schema::drop('oversea_reports');
    }
}
