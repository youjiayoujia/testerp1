<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLogisticsRuleLimits extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('logistics_rule_limits', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('logistics_rule_id')->comment('物流分配id')->default(0);
            $table->integer('logistics_limit_id')->comment('物流限制')->default(0);
            $table->enum('type', ['0', '1', '2'])->comment('限制类型/含/不含/可以含')->default('0');
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
        Schema::drop('logistics_rule_limits');
    }
}
