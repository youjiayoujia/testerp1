<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShipmentCostItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shipment_cost_items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('parent_id')->comment('父id')->default(0);
            $table->string('hang_number')->comment('挂号码')->default(NULL);
            $table->integer('package_id')->comment('包裹id')->default(0);
            $table->string('type')->comment('包裹类型')->default(NULL);
            $table->integer('logistics_id')->comment('物流方式')->default(0);
            $table->timestamp('shipped_at')->comment('发货时间')->default(NULL);
            $table->string('code')->comment('国家简称')->default(NULL);
            $table->string('destination')->comment('目的地')->default(NULL);
            $table->float('all_weight')->comment('计费重量(kg)')->default(0);
            $table->float('theory_weight')->comment('理论重量')->default(0);
            $table->float('all_cost')->comment('计费运费')->default(0);
            $table->float('theory_cost')->comment('理论运费')->default(0);
            $table->string('channel_name')->comment('渠道名')->default(NULL);
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
        Schema::drop('shipment_cost_items');
    }
}
