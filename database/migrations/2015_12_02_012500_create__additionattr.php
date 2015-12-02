<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdditionattr extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('additionattr', function (Blueprint $table) {
            $table->integer('FashionSel')->comment('选款的id')->default(NULL);
            $table->string('name',128)->comment('个性属性名')->default(NULL);
            $table->string('value')->comment('个性属性值')->default(NULL);
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
        Schema::drop('additionattr');
    }
}
