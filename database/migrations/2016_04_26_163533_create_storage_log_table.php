<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStorageLogTable extends Migration
{
   /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('storage_log', function (Blueprint $table) {
            $table->increments('id');
			$table->integer('purchaseItemId')->comment('采购条目Id')->nullable()->default(NULL);
			$table->integer('storage_quantity')->comment('入库数量')->default(0);
			$table->integer('user_id')->comment('入库者ID')->default(NULL);
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
        Schema::drop('storage_log');
    }
}
