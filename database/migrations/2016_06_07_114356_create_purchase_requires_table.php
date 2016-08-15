<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchaseRequiresTable extends Migration
{
     /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_requires', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('item_id')->comment('item_id');
            $table->integer('quantity')->comment('需求数量');
			 $table->enum('status',
                [
                    '0',
                    '1'
                ])->default('0')->comment('生成状态');
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
        Schema::drop('purchase_requires');
    }
}
