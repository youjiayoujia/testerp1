<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLogisticsSuppliers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('logistics_suppliers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->comment('物流商名称')->default(NULL);
            $table->integer('customer_id')->comment('客户ID')->default(NULL);
            $table->string('secret_key')->comment('密钥')->default(NULL);
            $table->enum('is_api', ['N', 'Y'])->comment('是否有API')->default(NULL);
            $table->string('client_manager')->comment('客户经理')->default(NULL);
            $table->string('manager_tel')->comment('客户经理联系方式')->default(NULL);
            $table->string('technician')->comment('技术人员')->default(NULL);
            $table->string('technician_tel')->comment('技术联系方式')->default(NULL);
            $table->string('remark')->comment('备注')->default(NULL);
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
        Schema::drop('logistics_suppliers');
    }
}
