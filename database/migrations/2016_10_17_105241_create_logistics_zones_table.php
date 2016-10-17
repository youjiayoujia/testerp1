<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLogisticsZonesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('logistics_zones', function(Blueprint $table) {
            $table->increments('id');
			$table->string('zone');
			$table->integer('logistics_id');
			$table->enum('type', array('first','second'))->default('first');
			$table->float('fixed_weight')->nullable();
			$table->float('fixed_price')->nullable();
			$table->float('continued_weight')->nullable();
			$table->float('continued_price')->nullable();
			$table->float('other_fixed_price')->nullable();
			$table->float('discount');
			$table->enum('discount_weather_all', array('0','1'))->default('0');
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
        Schema::drop('logistics_zones');
    }

}
