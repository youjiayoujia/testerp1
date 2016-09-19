<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFbaStockInfos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fba_stock_infos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('item_id')->comment('item Id')->default(0);
            $table->integer('account_id')->comment('渠道帐号')->default(0);
            $table->string('channel_sku')->comment('卖家sku')->default(NULL);
            $table->string('fnsku')->comment('fnsku')->default(NULL);
            $table->string('asin')->comment('asin')->default(NULL);
            $table->string('title')->comment('标题')->default(NULL);
            $table->integer('mfn_fulfillable_quantity')->default(NULL);
            $table->integer('afn_warehouse_quantity')->default(NULL);
            $table->integer('afn_fulfillable_quantity')->default(NULL);
            $table->integer('afn_unsellable_quantity')->default(NULL);
            $table->integer('afn_reserved_quantity')->default(NULL);
            $table->integer('afn_total_quantity')->default(NULL);
            $table->float('per_unit_volume')->default(NULL);
            $table->integer('afn_inbound_working_quantity')->default(NULL);
            $table->integer('afn_inbound_shipped_quantity')->default(NULL);
            $table->integer('afn_inbound_receiving_quantity')->default(NULL);
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
        Schema::drop('fba_stock_infos');
    }
}
