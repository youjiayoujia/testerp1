<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePicks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('picks', function (Blueprint $table) {
            $table->increments('id');
            $table->string('pick_id')->comment('拣货单号')->default(NULL);
            $table->enum('type', ['0', '1', '2'])->comment('拣货单状态')->default('0');
            $table->enum('status', ['0', '1', '2'])->comment('拣货单状态')->default('0');
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
        Schema::drop('picks');
    }
}
