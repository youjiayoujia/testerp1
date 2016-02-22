<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductVariationValues extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_variation_values', function (Blueprint $table) {
            $table->increments('id');
            $table->Integer('product_id')->comment('产品id')->nullable()->default(0);
            $table->string('variation_id')->comment('属性id')->nullable()->default(NULL);
            $table->integer('variation_value_id')->comment('asdf')->default(0);
            $table->string('variation_value')->comment('属性值')->nullable()->default(NULL);
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
        Schema::drop('product_variation_values');
    }
}
