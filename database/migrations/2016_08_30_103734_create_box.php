<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBox extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('oversea_boxs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('boxNum')->comment('箱号')->default(NULL);
            $table->float('fee')->commen('物流费')->default(0);
            $table->integer('logistics_id')->comment('物流方式')->default(0);
            $table->string('tracking_no')->comment('追踪号')->default(NULL);
            $table->decimal('length', 6, 2)->comment('长')->default(0);
            $table->decimal('width', 6, 2)->comment('宽')->default(0);
            $table->decimal('height', 6, 2)->comment('高')->default(0);
            $table->decimal('weight', 7, 3)->comment('重量')->default(0);
            $table->integer('parent_id')->comment('父id')->default(0);
            $table->enum('status', [0, 1])->comment('发货标记')->default(0);
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
        Schema::drop('oversea_boxs');
    }
}
