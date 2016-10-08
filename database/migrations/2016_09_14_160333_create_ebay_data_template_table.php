<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEbayDataTemplateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ebay_data_template', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->comment('模板名称')->default('');
            $table->integer('site')->comment('站点')->default(0);
            $table->integer('warehouse')->comment('仓库')->default(1);
            $table->string('start_weight')->comment('结束重量')->default('');
            $table->string('end_weight')->comment('结束重量')->default('');
            $table->string('start_price')->comment('起始价格')->default('');
            $table->string('end_price')->comment('结束价格')->default('');
            $table->string('location')->comment('物品所在地')->default('');
            $table->string('country')->comment('国家')->default('');
            $table->string('postal_code')->comment('邮编')->default('');
            $table->string('dispatch_time_max')->comment('处理天数')->default('');
            $table->text('buyer_requirement')->comment('买家要求')->default('');
            $table->text('return_policy')->comment('退货政策')->default('');
            $table->text('shipping_details')->comment('物流设置')->default('');
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
        Schema::drop('ebay_data_template');
    }
}
