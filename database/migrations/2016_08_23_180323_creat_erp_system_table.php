<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatErpSystemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::create('erp_system', function (Blueprint $table) {
            $table->increments('system_value_id');
            $table->string('system_value_name');
            $table->text('system_value');
            $table->string('system_value_dscp');
            $table->text('system_remark');
            $table->integer('sort')->comment('排序')->default(0);
          
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
        Schema::drop('erp_system');
    }
}
