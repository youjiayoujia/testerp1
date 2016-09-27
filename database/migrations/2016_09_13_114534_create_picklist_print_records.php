<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePicklistPrintRecords extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('picklist_print_records', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('picklist_id')->comment('拣货单id')->default(0);
            $table->integer('user_id')->comment('操作人id')->default(0);
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
        Schema::drop('picklist_print_records');
    }
}
