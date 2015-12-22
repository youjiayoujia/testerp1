<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLogisticsShipping extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('logistics_shipping', function (Blueprint $table) {
            $table->increments('id');
            $table->string('short_code')->comment('物流方式简码')->default(NULL);
            $table->string('logistics_type')->comment('物流方式名称')->default(NULL);
            $table->string('species')->comment('种类')->default(NULL);
            $table->string('warehouse')->comment('仓库')->default(NULL);
            $table->string('logistics_id')->comment('物流商')->default(NULL);
            $table->string('type_id')->comment('物流商物流方式')->default(NULL);
            $table->string('url')->comment('物流追踪网址')->default(NULL);
            $table->string('api_docking')->comment('API对接方式')->default(NULL);
            $table->string('is_enable')->comment('是否启用')->default(NULL);
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
        Schema::drop('logistics_shipping');
    }
}
