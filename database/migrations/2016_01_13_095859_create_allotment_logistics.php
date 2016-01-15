<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAllotmentLogistics extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('allotment_logistics', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('allotments_id')->comment('调整单号id')->default(NULL);
            $table->string('type')->comment('物流方式')->default(NULL);
            $table->string('code')->comment('物流跟踪号')->default(NULL);
            $table->float('fee')->comment('物流费')->default(NULL);
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
        Schema::drop('allotment_logistics');
    }
}
