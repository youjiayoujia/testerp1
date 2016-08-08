<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePicklistErrorLists extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('picklist_error_lists', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('item_id')->comment('itemId')->default(0);
            $table->string('packageNum')->comment('packageNum')->default(NULL);
            $table->integer('warehouse_position_id')->comment('库位')->default(0);
            $table->integer('warehouse_id')->comment('仓库')->default(0);
            $table->integer('quantity')->comment('库存')->default(0);
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
        Schema::drop('picklist_error_lists');
    }
}
