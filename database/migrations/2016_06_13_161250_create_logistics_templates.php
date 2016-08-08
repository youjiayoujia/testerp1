<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLogisticsTemplates extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('logistics_templates', function (Blueprint $table) {
            $table->increments('id')->comment('编号');
            $table->string('name')->comment('面单名称');
            $table->string('view')->comment('视图');
            $table->string('size')->comment('尺寸');
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
        Schema::drop('logistics_templates');
    }
}
