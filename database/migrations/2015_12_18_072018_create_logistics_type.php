<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLogisticsType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('logistics_type', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type')->comment('物流商物流方式')->default(NULL);
            $table->integer('logistics_id')->comment('物流商')->default(NULL);
            $table->string('remark')->comment('备注')->default(NULL);
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
        Schema::drop('logistics_type');
    }
}
