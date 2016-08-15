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
            $table->string('code')->comment('物流方式简码')->default(NULL);
            $table->string('name')->comment('物流方式名称')->default(NULL);
            $table->integer('warehouse_id')->comment('仓库')->default(NULL);
            $table->integer('logistics_supplier_id')->comment('物流商')->default(NULL);
            $table->string('type')->comment('物流商物流方式')->default(NULL);
            $table->string('url')->comment('物流追踪网址')->default(NULL);
            $table->enum('docking',
                [
                    'MANUAL',
                    'SELFAPI',
                    'API',
                    'CODE'
                ])->default('CODE')->comment('对接方式');
            $table->integer('logistics_catalog_id')->comment('物流分类')->nullable()->default(0);
            $table->integer('logistics_email_template_id')->comment('回邮模版')->nullable()->default(0);
            $table->integer('logistics_template_id')->comment('面单模版')->nullable()->default(0);
            $table->string('pool_quantity')->comment('号码池数量')->nullable()->default(NULL);
            $table->string('limit')->comment('物流限制')->nullable()->default(NULL);
            $table->enum('is_enable', ['0', '1'])->comment('是否启用');
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
