<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLogisticsEmailTemplates extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('logistics_email_templates', function (Blueprint $table) {
            $table->increments('id')->comment('编号');
            $table->string('customer')->comment('协议客户');
            $table->string('address')->comment('发件地址');
            $table->string('zipcode')->comment('邮编');
            $table->string('phone')->comment('电话');
            $table->string('unit')->comment('退件单位');
            $table->string('sender')->comment('寄件人');
            $table->string('remark')->comment('备注')->nullable()->default(NULL);
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
        Schema::drop('logistics_email_templates');
    }
}
