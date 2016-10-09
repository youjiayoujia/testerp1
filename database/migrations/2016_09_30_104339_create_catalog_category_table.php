<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCatalogCategoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('catalog_category', function (Blueprint $table) {
            $table->increments('id');
            $table->string('cn_name')->comment('一级品类分类中文名');
            $table->string('en_name')->comment('一级品类分类中文名');
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
        Schema::drop('catalog_category');
    }
}
