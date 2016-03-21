<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePackageLogistics extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('package_logistics', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('package_id')->comment('package id')->default(0);
            $table->string('logistic_code')->comment('物流跟踪号')->default(0);
            $table->float('fee')->comment('物流费')->default(0);
            $table->text('remark')->comment('备注')->default(NULL);
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
        Schema::drop('package_logistics');
    }
}
