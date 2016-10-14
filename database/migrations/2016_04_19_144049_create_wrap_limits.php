<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWrapLimits extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wrap_limits', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->comment('包装限制名称')->default(NULL);
            $table->integer('code')->comment('编码')->default(NULL);
            $table->integer('sort')->comment('优先级')->default(NULL);
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
        Schema::drop('wrap_limits');
    }
}
