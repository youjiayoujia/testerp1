<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdjustForms extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('adjust_forms', function (Blueprint $table) {
            $table->increments('id');
            $table->string('adjust_form_id')->comment('调整单号id')->default(NULL);
            $table->integer('warehouses_id')->comment('仓库id')->default(NULL);
            $table->integer('adjust_man_id')->comment('调整人')->default(NULL);
            $table->date('adjust_time')->comment('调整时间')->default(NULL);
            $table->text('remark')->comment('备注')->default(NULL);
            $table->integer('check_man_id')->comment('审核人')->default(NULL);
            $table->date('check_time')->comment('审核时间')->default(NULL);
            $table->enum('status', ['N', 'Y'])->comment("审核状态")->default('N');
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
        Schema::drop('adjust_forms');
    }
}
