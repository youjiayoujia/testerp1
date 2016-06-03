<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductMultiOptions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_multi_options', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('product_id')->comment('product_id');
            $table->integer('channel_id')->comment('channel_id');
            $table->string('de_name')->comment('de_name');
            $table->string('de_description')->comment('de_description');
            $table->string('de_keywords')->comment('de_keywords');
            $table->string('fr_name')->comment('fr_name');
            $table->string('fr_description')->comment('fr_description');
            $table->string('fr_keywords')->comment('fr_keywords');
            $table->string('it_name')->comment('it_name');
            $table->string('it_description')->comment('it_description');
            $table->string('it_keywords')->comment('it_keywords');
            $table->string('zh_name')->comment('it_name');
            $table->string('zh_description')->comment('it_description');
            $table->string('zh_keywords')->comment('it_keywords');
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
        Schema::drop('product_multi_options');
    }
}
