<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLogisticses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('logisticses', function (Blueprint $table) {
            $table->increments('id');
            $table->string('short_code')->comment('物流方式简码')->default(NULL);
            $table->string('logistics_type')->comment('物流方式名称')->default(NULL);
            $table->string('species')->comment('种类')->default(NULL);
            $table->integer('warehouse_id')->comment('仓库')->default(NULL);
            $table->integer('logistics_supplier_id')->comment('物流商')->default(NULL);
            $table->string('type')->comment('物流商物流方式')->default(NULL);
            $table->string('url')->comment('物流追踪网址')->default(NULL);
            $table->string('api_docking')->comment('API对接方式')->default(NULL);
            $table->enum('is_enable', ['N', 'Y'])->comment('是否启用')->default(NULL);
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
        Schema::drop('logisticses');
    }
}
