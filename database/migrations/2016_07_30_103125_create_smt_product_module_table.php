<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSmtProductModuleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('smt_product_module', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('module_id')->comment('模板ID');
            $table->integer('token_id')->comment('账号ID');
            $table->string('module_name')->comment('模板名称');
            $table->string('module_type')->comment('模板类型');
            $table->string('module_status')->comment('模板状态');
            $table->string('aliMemberId')->comment('阿里账号ID');
            $table->text('displayContent')->comment('显示内容');
            $table->text('moduleContents')->comment('模板内容状态');
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
