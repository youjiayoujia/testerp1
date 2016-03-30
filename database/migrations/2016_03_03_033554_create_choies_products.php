<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChoiesProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('choies_product', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('product_id')->comment('产品ID')->nullable()->default(null);
            $table->integer('channel_id')->comment('渠道ID')->nullable()->default(null);
            $table->string('choice_info')->comment('平台信息')->nullable()->default(null);
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
        Schema::drop('product_images');
    }
}
