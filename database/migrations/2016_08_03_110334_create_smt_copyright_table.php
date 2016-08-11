<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSmtCopyrightTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::create('smt_copyright', function (Blueprint $table) {
            $table->increments('id');
            $table->string('account')->comment('账号');       
            $table->string('sku')->comment('sku');
            $table->string('pro_id')->comment('产品广告ID');
            $table->string('complainant')->comment('投诉人');
            $table->string('reason')->comment('侵权原因'); 
            $table->string('trademark')->comment('商标名');
            $table->string('ip_number')->comment('知识产权编号');
            $table->string('degree')->comment('严重程度');
            $table->string('violatos_number')->comment('违规编号');
            $table->string('violatos_big_type')->comment('违规大类');
            $table->string('violatos_small_type')->comment('违规小类');
            $table->tinyInteger('status')->comment('有效状态,1为有效，0为无效,默认为有效')->default('1');
            $table->string('score')->comment('分值');
            $table->string('violatos_start_time')->comment('违规生效时间');
            $table->string('violatos_fail_time')->comment('违规失效时间');
            $table->string('seller')->comment('销售');
            $table->string('remarks')->comment('备注信息');
            $table->string('import_time')->comment('导入时间');
            $table->string('import_uid')->comment('导入人UID');
            $table->tinyInteger('is_del')->comment('是否没删除,1:是,0:否')->default('1');
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
