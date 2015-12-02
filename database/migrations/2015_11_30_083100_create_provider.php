<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProvider extends Migration
{
    /**
     * Run the migrations.
     *
     * id  =>  provider_id 用于标志供货商id
     * name => 供货商名字
     * address => 供货商地址，可通过经纬度来表示
     * url => 供货商网址
     * tele => 供货商联系方式,电话
     * purchase_id => 采购员id
     * level => 给供货商的评级   1-5 依次到高
     * 
     * @return void
     */
    public function up()
    {
        Schema::create('providers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name',128)->comment('供货商名字')->default(NULL);
            $table->string('address')->comment('供货商地址，经纬度')->default(NULL);
            $table->enum('isonline_provider',[0, 1])->comment('是否是线上供货商')->default(1);
            $table->string('url',128)->comment('供货商url')->default(NULL);
            $table->string('telephone')->comment('供货商联系方式')->default(NULL);
            $table->integer('purchase_id')->comment('采购员id')->default(NULL);
            $table->enum('level',[1,2,3,4,5])->comment('供货商评级')->default(3);
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
        Schema::drop('providers');
    }
}
