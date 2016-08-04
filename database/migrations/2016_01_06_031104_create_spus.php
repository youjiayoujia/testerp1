<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSpus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('spus', function (Blueprint $table) {
            $table->increments('id');
            $table->string('spu')->comment('spu')->nullable()->default(NULL);
            $table->integer('product_require_id')->comment('product_require_id')->nullable()->default(NULL);
            $table->integer('purchase')->comment('采购人')->nullable()->default(NULL);
            $table->integer('edit_user')->comment('编辑人')->nullable()->default(NULL);
            $table->integer('image_edit')->comment('美工人')->nullable()->default(NULL);
            $table->integer('developer')->comment('开发人')->nullable()->default(NULL);
            $table->tinyInteger('status')->comment('状态')->nullable()->default(0);
            $table->string('remark')->comment('remark')->nullable()->default(NULL);
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
        Schema::drop('spus');
    }
}
