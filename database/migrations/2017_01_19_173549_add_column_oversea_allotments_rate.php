<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnOverseaAllotmentsRate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('oversead_allotments', function (Blueprint $table) {
            $table->decimal('actual_rate_value',6,2)->comment('实际总税金')->default(0);
            $table->string('tracking_no')->comment('货代追踪码')->default('');
            $table->text('remark')->comment('备注')->default('');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('oversead_allotments', function (Blueprint $table) {
            $table->dropColumn('actual_rate_value');
            $table->dropColumn('tracking_no');
            $table->dropClumn('remark');
        });
    }
}
