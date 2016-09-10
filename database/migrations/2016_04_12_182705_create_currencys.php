<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCurrencys extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('currencys', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code')->comment('货币简称')->default(NULL);
            $table->string('name')->comment('货币名称')->default(NULL);
            $table->string('identify')->comment('标识')->comment(NULL);
            $table->decimal('rate',11,9)->comment('汇率')->comment(1.0);
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
        Schema::drop('currencys');
    }
}
