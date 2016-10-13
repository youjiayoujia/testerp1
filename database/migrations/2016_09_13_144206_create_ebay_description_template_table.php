<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEbayDescriptionTemplateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ebay_description_template', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->comment('模板名称')->default('');
            $table->integer('site')->comment('站点')->default(0);
            $table->integer('warehouse')->comment('仓库')->default(1);
            $table->text('description')->comment('描述HTML')->default('');
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
        Schema::drop('ebay_description_template');
    }
}
