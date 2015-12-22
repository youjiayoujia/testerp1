<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemin extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('itemins', function (Blueprint $table) {
            $table->increments('id');
            $table->string('sku')->comment('sku')->default(NULL);
            $table->string('amount')->comment('数量')->default(NULL);
            $table->integer('total_amount')->comment('总金额')->default(NULL);
            $table->text('remark')->comment('备注')->default(NULL);
            $table->integer('typeof_itemin')->comment('入库类型id')->default(NULL);
            $table->string('typeof_itemin_id', 64)->comment('入库来源id')->default(NULL);
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
        Schema::drop('itemins');
    }
}
