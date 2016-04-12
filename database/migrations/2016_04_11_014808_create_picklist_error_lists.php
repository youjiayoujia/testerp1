<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePicklistErrorLists extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('picklist_error_lists', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('picklist_id')->comment('拣货单id')->default(0);
            $table->integer('package_id')->comment('package id号')->default(0);
            $table->enum('status', ['0', '1'])->comment('状态')->default(0);
            $table->integer('process_by')->comment('处理人')->default(0);
            $table->timestamp('process_time')->comment('处理时间')->default(0);
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
        Schema::drop('picklist_error_lists');
    }
}
