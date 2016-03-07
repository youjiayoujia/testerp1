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
            $table->string('name', 128)->comment('库位名')->default('0');
            $table->integer('warehouse_id')->comment('仓库id')->default(0);
            $table->text('remark')->comment('库位描述')->default(NULL);
            $table->enum('size', ['big', 'middle', 'small'])->comment('库位大小')->default('middle');
            $table->float('length')->comment('长')->default(1);
            $table->float('width')->comment('宽')->default(1);
            $table->float('height')->comment('高')->default(1);
            $table->enum('is_available', ['0', '1'])->comment('库位是否启用')->default('0');
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
