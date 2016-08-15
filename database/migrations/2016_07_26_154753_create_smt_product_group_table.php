<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSmtProductGroupTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::create('smt_product_group', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('token_id')->comment('账号id');       
            $table->integer('group_id')->comment('分组id');
            $table->string('group_name')->comment('分组名称');
            $table->integer('parent_id')->comment('分组父ID');
            $table->dateTime('last_update_time')->comment('最后同步时间'); 
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
        //
    }
}
