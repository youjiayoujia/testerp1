<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRefundOperateLogs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('refund_operate_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('operate_id')->comment('操作人');
/*            $table->('operate_id')->comment('操作人');
            、、$table->('')->comment('操作人');*/
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
        Schema::drop('refund_operate_logs');
    }
}
