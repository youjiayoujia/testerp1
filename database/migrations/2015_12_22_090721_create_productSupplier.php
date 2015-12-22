<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductSupplier extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_suppliers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name',128)->comment('供货商名称')->default(NULL);
            $table->string('province')->comment('供货商省')->default(NULL);
            $table->string('city')->comment('供货商市')->default(NULL);
            $table->string('address')->comment('供货商地址，经纬度')->default(NULL);
            $table->enum('type',['online','offline'])->comment('是否是线上供货商')->default('offline');
            $table->string('url',128)->comment('供货商url')->default(NULL);
            $table->string('telephone')->comment('供货商联系方式')->default(NULL);
            $table->integer('purchase_id')->comment('采购员id')->default(NULL);
            $table->enum('level',[1,2,3,4,5])->comment('供货商评级')->default(3);
            $table->integer('created_by')->comment('创建人id')->default(NULL);
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
        Schema::drop('product_suppliers');
    }
}
