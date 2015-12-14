<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('product_id')->comment('产品ID')->nullable()->default(NULL);
            $table->string('item_set_id')->comment('产品set_id')->nullable()->default(NULL);            
            $table->integer('item_attribute_id')->comment('产品attribute_id')->nullable()->default(NULL);
            $table->integer('item_specification_id')->comment('产品specification_id')->nullable()->default(NULL);
            $table->integer('weight')->comment('重量')->nullable()->default(NULL);
            $table->integer('inventory')->comment('库存')->nullable()->default(NULL);
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
        Schema::drop('items');
    }
}
