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
            $table->string('detail_address')->comment('仓库详细地址')->default(NULL);
            $table->enum('type', ['本地仓库', '海外仓库', '第三方仓库'])->comment('仓库类型')->default('本地仓库');
            $table->integer('volumn')->comment('仓库容积(单位M3)')->default(NULL);
            $table->enum('is_available', ['N', 'Y'])->comment('仓库是否启用')->default('N');
            $table->enum('is_default', ['N', 'Y'])->comment('仓库是否是默认仓库')->default('N');
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

