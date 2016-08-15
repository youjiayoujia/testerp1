<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomsClearances extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customes_clearances', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('product_id')->comment('product_id')->default(0);
            $table->string('cn_name')->comment('通关中文名')->default(NULL);
            $table->mediuminteger('hs_code')->comment('hs_code')->default(0);
            $table->string('unit')->comment('单位')->default('0');
            $table->text('f_model')->comment('属性描述')->default(NULL);
            $table->enum('status', ['0', '1'])->comment('状态')->default(0);
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
        Schema::drop('customes_clearances');
    }
}
