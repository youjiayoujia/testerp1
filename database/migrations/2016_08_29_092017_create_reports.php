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
            $table->string('fba_address')->comment('fba地址')->default(NULL);
            $table->integer('plan_id')->comment('plan  ID')->default(0);
            $table->integer('shipment_id')->comment('shipment id')->default(0);
            $table->integer('reference_id')->comment('reference_id')->default(0);
            $table->string('shipment_name')->comment('shipment name')->default(NULL);
            $table->enum('status', ['NEW', 'PASS', 'FAIL', 'PICKING', 'PACKING', 'PACKED', 'SHIPPED'])->comment('状态')->default('NEW');
            $table->enum('print_status', ['UNPRINT', 'PRINTED'])->comment('打印状态')->default('UNPRINT');
            $table->string('inStock_status')->comment('入库状态')->default(NULL);
            $table->string('from_address')->comment('始发地址')->default(NULL);
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
