<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLogisticsCodes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('logistics_codes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('logistics_id')->comment('物流方式')->default(NULL);
            $table->string('code')->comment('跟踪号')->default("");
            $table->integer('package_id')->comment('包裹ID')->nullable();
            $table->string('status')->comment('状态')->default('N');
            $table->date('used_at')->nullable()->comment('使用时间')->default(NULL);
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
        Schema::drop('logistics_codes');
    }
}