<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWarehouse extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('warehouses', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name',128)->comment('仓库名')->default(NULL);
            $table->string('province')->comment('省')->default(NULL);
            $table->string('city')->comment('市')->default(NULL);
            $table->enum('type', ['local', 'oversea', 'third'])->comment('仓库类型')->default('local');
            $table->integer('volumn')->comment('仓库容积(单位M3)')->default(NULL);
            $table->enum('is_available', ['0', '1'])->comment('仓库是否启用')->default('0');
            $table->enum('is_default', ['0', '1'])->comment('仓库是否是默认仓库')->default('0');
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
        Schema::drop('warehouses');
    }
}
