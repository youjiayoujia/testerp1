<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWarehousePosition extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('warehouse_positions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 128)->comment('库位名')->default(NULL);
            $table->integer('warehouses_id')->comment('仓库id')->default(NULL);
            $table->text('remark')->comment('库位描述')->default(NULL);
            $table->enum('size', ['大', '中', '小'])->comment('库位大小')->default('中');
            $table->enum('is_available', ['N', 'Y'])->comment('库位是否启用')->default('N');
            $table->timestamps();
            $table->softdeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('warehouse_positions');
    }
}
