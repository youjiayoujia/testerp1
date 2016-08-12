<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ShipmentCost extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shipment_costs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('shipmentCostNum')->comment('批次号')->default(NULL);
            $table->decimal('all_weight', '7', '3')->comment('计费总重量')->default(NULL);
            $table->decimal('theory_weight', '7', '3')->comment('理论重量')->default(NULL);
            $table->float('all_shipment_cost')->comment('总运费')->default(0);
            $table->float('theory_shipment_cost')->comment('理论总运费')->default(0);
            $table->text('average_price')->comment('各渠道均价')->default(NULL);
            $table->integer('import_by')->comment('导入人')->default(0);
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
        Schema::drop('shipment_costs');
    }
}
