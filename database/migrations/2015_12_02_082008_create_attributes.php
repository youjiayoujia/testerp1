<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAttributes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attributes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('catalog_id')->comment('属性名')->default(NULL);
            $table->string('name')->comment('属性名')->default(NULL);
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
        Schema::drop('attributes');
    }
}
