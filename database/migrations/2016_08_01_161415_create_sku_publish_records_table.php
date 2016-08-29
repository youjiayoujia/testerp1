<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSkuPublishRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sku_publish_records', function (Blueprint $table) {
            $table->increments('id');
            $table->string('SKU')->comment('产品sku');
            $table->integer('userID')->comment('用户id');
            $table->dateTime('publishTime')->comment('发布时间');
            $table->integer('platTypeID')->comment('平台id');
            $table->integer('publishPlat')->comment('发布平台');
            $table->string('sellerAccount')->comment('销售帐号');
            $table->string('itemNumber');
            $table->text('publishViewUrl');
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
        //
    }
}
