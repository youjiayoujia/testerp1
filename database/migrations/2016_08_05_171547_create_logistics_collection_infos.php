<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLogisticsCollectionInfos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('logistics_collection_infos', function (Blueprint $table) {
            $table->increments('id')->comment('ID');
            $table->string('bank')->comment('收款银行');
            $table->string('account')->comment('收款账户');
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
        Schema::drop('logistics_collection_infos');
    }
}
