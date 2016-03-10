<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePickListToPackages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pick_list_to_packages', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('pick_list_id')->comment('pick_list号')->default(0);
            $table->integer('package_id')->comment('package号')->default(0);
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
        Schema::drop('pick_list_to_packages');
    }
}
