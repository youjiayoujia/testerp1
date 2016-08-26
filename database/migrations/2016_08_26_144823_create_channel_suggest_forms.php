<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChannelSuggestForms extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('channel_suggest_forms', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('item_id')->comment('item 号')->default(0);
            $table->string('channel_sku')->comment('渠道sku')->default(NULL);
            $table->string('fnsku')->comment('fba sku')->default(NULL);
            $table->integer('fba_all_quantity')->comment('fba总数量')->default(0);
            $table->integer('fba_available_quantity')->comment('fba可用数量')->default(0);
            $table->integer('all_quantity')->comment('本地总数量')->default(0);
            $table->integer('sales_in_seven')->comment('7天销量')->default(0);
            $table->integer('sales_in_fourteen')->comment('14天销量')->default(0);
            $table->integer('suggest_quantity')->comment('建议数量')->default(0);
            $table->integer('account_id')->comment('渠道帐号')->default(0);
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
        Schema::drop('channel_suggest_forms');
    }
}
