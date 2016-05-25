<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderComplaintsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_complaints', function (Blueprint $table) {
            $table->increments('id');
			$table->integer('order_item_id')->comment('投诉产品号')->nullable()->default(NULL);
			$table->integer('create_user_id')->comment('创建人')->default(NULL);
			$table->string('complaint_email')->comment('投诉email')->default(NULL);
			$table->integer('complaint_type')->comment('投诉类型')->default(0);
            $table->string('question')->comment('问题描述')->nullable()->default(NULL);
			$table->string('complaint_country')->comment('投诉来源国')->nullable()->default(NULL);
			$table->integer('update_user_id')->comment('更新人')->default(NULL);
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
        Schema::drop('order_complaints');
    }
}
