<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
class CreateProductFeatureValues extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_feature_values', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('product_id')->comment('状态')->nullable()->default(0);
            $table->integer('feature_id')->comment('属性id')->nullable()->default(0);
            $table->integer('feature_value_id')->comment('属性值id')->nullable()->default(0);
            $table->string('feature_value')->comment('属性值')->nullable()->default(NULL);
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
        Schema::drop('product_feature_values');
    }
}