<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEbayInfoToEbayPublishProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('ebay_publish_product', function (Blueprint $table) {
            $table->text('buyer_requirement')->comment('买家要求')->default('');
            $table->string('country')->comment('国家')->default('');
            $table->integer('description_id')->comment('描述模板id')->default(0);
            $table->integer('warehouse')->comment('仓库id')->default(1);
            $table->text('description_picture')->comment('描述图片');
            $table->text('note')->comment('api返回错误信息');
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

        Schema::table('ebay_publish_product', function (Blueprint $table) {
            $table->dropColumn('buyer_requirement');
            $table->dropColumn('country');
            $table->dropColumn('description_id');
            $table->dropColumn('description_picture');
            $table->dropColumn('note');
        });
    }
}
