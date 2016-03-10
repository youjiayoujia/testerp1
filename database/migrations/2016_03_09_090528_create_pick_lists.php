<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePickLists extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pick_lists', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('pick_id')->comment('pick的id号')->default(0);
            $table->integer('item_id')->comment('item号')->default(0);
            $table->integer('quantity')->comment('数量')->default(0);
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
        Schema::drop('pick_lists');
    }
}
