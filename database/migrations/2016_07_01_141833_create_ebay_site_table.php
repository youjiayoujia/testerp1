<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEbaySiteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ebay_site', function (Blueprint $table) {
            $table->increments('id');
            $table->string('site')->comment('站点名称')->default('');
            $table->integer('site_id')->comment('站点id')->default(0);
            $table->string('currency')->comment('币种')->default('');
            $table->integer('detail_version')->comment('版本')->default(0);
            $table->string('returns_accepted')->comment('退货政策')->default('');
            $table->string('returns_with_in')->comment('退货天数')->default('');
            $table->string('shipping_costpaid_by')->comment('运费承担者')->default('');
            $table->string('refund')->comment('退货方式')->default('');
            $table->enum('is_use', ['0', '1'])->comment('1 启用 0 不启用')->default('1');

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
        Schema::drop('ebay_site');
    }
}
