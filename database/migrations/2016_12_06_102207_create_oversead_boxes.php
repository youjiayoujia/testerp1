<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOverseadBoxes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('oversead_boxes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('boxnum')->comment('箱号')->default(NULL);
            $table->integer('parent_id')->commnet('调拨单id')->default(0);
            $table->string('volumn')->comment('体积')->default(NULL);
            $table->integer('logistics_id')->comment('物流id')->default(0);
            $table->string('tracking_no')->comment('追踪号')->default(NULL);
            $table->decimal('weight', 6, 3)->comment('重量')->default(0);
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
        Schema::drop('oversead_boxes');
    }
}
