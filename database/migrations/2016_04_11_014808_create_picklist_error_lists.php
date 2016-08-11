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
            $table->integer('item_id')->comment('item_id')->default(0);
            $table->integer('packageNum')->comment('package号组合')->default(0);
            $table->enum('status', ['0', '1'])->comment('状态')->default(0);
            $table->integer('process_by')->comment('处理人')->default(0);
            $table->timestamp('process_time')->comment('处理时间')->default(NULL);
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
