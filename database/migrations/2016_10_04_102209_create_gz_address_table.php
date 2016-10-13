<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGzAddressTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::create('erp_gz_address', function (Blueprint $table) {
            $table->increments('id');
            $table->string('sender')->comment('寄件人姓名');
            $table->string('address')->comment('寄件人地址');
            $table->tinyInteger('useNumber')->comment('使用次数');
            $table->date('updateTime')->comment('更新时间');
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
        Schema::drop('erp_gz_address');
    }
}
