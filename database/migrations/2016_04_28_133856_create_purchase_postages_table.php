<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchasePostagesTable extends Migration
{
	  /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_postages', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('purchase_item_id')->comment('采购条目ID')->nullable()->default(0);
            $table->integer('purchase_order_id')->comment('采购单ID')->nullable()->default(0);
            $table->string('post_coding')->comment('采购物流单号')->nullable()->default(NULL);
            $table->decimal('postage',8,2)->comment('采购物流运费价')->nullable()->default(0); 
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
        Schema::drop('purchase_postages');
    }
}