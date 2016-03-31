<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductAbnormalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::create('product_abnormals', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('spu_id')->comment('异常spuId')->nullable()->default(null);
			$table->integer('type')->comment('异常类型')->nullable()->default(NULL);
			$table->integer('user_id')->comment('操作者ID')->nullable()->default(NULL);
			$table->integer('image_id')->comment('错误图片ID')->nullable()->default(NULL);   
            $table->integer('status')->comment('处理状态 0：正常，1：预非正常，2：核实异常')->nullable()->default(1);
			$table->integer('update_userId')->comment('处理人')->nullable()->default(NULL);
			$table->string('remark')->comment('异常说明')->nullable()->default(NULL);
			$table->date('arrival_time')->comment('到货时间')->nullable()->default(NULL);
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
