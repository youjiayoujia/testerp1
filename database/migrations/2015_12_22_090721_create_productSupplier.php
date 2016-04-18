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
            $table->string('address')->comment('供货商地址')->default(NULL);
            $table->string('company')->comment('公司名称')->default(NULL);
            $table->enum('type',['0', '1', '2'])->comment('供货商类型')->default('0');
            $table->string('url',64)->comment('供货商url')->default(NULL);
            $table->string('official_url',64)->comment('供货商官网')->default(NULL);
            $table->string('contact_name', 16)->comment('供货商联系人')->default(NULL);
            $table->string('telephone')->comment('供货商联系方式')->default(NULL);
            $table->string('email')->comment('电子邮件')->default(NULL);
            $table->integer('purchase_id')->comment('采购员id')->default(NULL);
            $table->integer('level_id')->comment('供货商评级id')->default(0);
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
