<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePicklistItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('picklist_items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('picklist_id')->comment('pick的id号')->default(0);
            $table->integer('item_id')->comment('item号')->default(0);
            $table->integer('warehouse_position_id')->comment('item号')->default(NULL);
            $table->integer('quantity')->comment('数量')->default(0);
            $table->enum('type', ['SINGLE', 'SINGLEMULTI', 'MULTI'])->comment('类型')->default('SINGLE');
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
        Schema::drop('picklist_items');
    }
}
