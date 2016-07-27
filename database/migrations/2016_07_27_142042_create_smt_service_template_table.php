<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSmtServiceTemplateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('smt_service_template', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('token_id')->comment('账号id');
            $table->integer('serviceID')->comment('线上服务id');
            $table->string('serviceName')->comment('服务名称');
            $table->dateTime('last_update_time')->comment('最后更新时间');
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
        //
    }
}
