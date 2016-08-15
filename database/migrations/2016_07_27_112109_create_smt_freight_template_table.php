<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSmtFreightTemplateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('smt_freight_template', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('token_id')->comment('账号id');
            $table->integer('templateId')->comment('模板ID');
            $table->string('templateName')->comment('模板名称');
            $table->tinyInteger('default')->default('0')->comment('是否默认模板,1为是');
            $table->text('freightSettingList')->comment('设置信息');
            $table->dateTime('last_update_time')->comment('最后同步时间');
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
