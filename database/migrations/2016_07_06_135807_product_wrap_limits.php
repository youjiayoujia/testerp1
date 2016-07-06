<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ProductWrapLimits extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_wrap_limits', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('product_id')->comment('product_id')->default(NULL);
            $table->integer('wrap_limits_id')->comment('product_id')->default(NULL);
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
        Schema::drop('product_wrap_limits');
    }
}
