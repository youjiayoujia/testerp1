<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCatalogChannels extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('catalog_channels', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('catalog_id')->comment('catalog_id');
            $table->integer('channel_id')->comment('channel_id');
            $table->decimal('rate',6,2)->comment('费率');
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
        Schema::drop('catalog_channels');
    }
}
