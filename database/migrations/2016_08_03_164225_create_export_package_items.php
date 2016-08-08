<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExportPackageItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('export_package_items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('parent_id')->comment('父id')->default(0);
            $table->string('name')->comment('字段名')->default(NULL);
            $table->string('level')->comment('排序')->comment('Z');
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
        Schema::drop('export_package_items');
    }
}
