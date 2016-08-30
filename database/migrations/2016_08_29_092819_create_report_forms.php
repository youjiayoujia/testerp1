<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReportForms extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('oversea_report_forms', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('parent_id')->comment('父id')->default(0);
            $table->integer('item_id')->comment('item id')->default(0);
            $table->integer('warehouse_position_id')->comment('warehouse_position_id')->default(0);
            $table->string('sku')->comment('sku')->default(NULL);
            $table->string('fnsku')->comment('fnsku')->default(NULL);
            $table->integer('report_quantity')->comment('report_quantity')->default(0);
            $table->integer('out_quantity')->comment('出库数量')->default(0);
            $table->integer('inbox_quantity')->comment('分拣数量')->default(0);
            $table->string('boxNum')->comment('箱号')->default(0);
            $table->integer('in_quantity')->comment('fba入库数量')->default(0);
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
        Schema::drop('oversea_report_forms');
    }
}
