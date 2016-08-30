<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBoxForms extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('oversea_box_forms', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('parent_id')->comment('父id')->default(0);
            $table->string('sku')->comment('sku')->default(0);
            $table->string('fnsku')->comment('fnsku')->default(0);
            $table->integer('quantity')->comment('数量')->default(0);
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
        Schema::drop('oversea_box_forms');
    }
}
