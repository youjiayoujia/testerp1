<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterPicklists extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('picklists', function (Blueprint $table) {
            $table->timestamp('pick_at')->comment('拣货时间');
            $table->integer('inbox_by')->comment('分拣人');
            $table->timestamp('inbox_at')->comment('分拣时间');
            $table->integer('pack_by')->comment('包装人');
            $table->integer('pack_at')->comment('包装时间');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('picklists', function (Blueprint $table) {
            $table->dropColumn('pick_at');
            $table->dropColumn('inbox_by');
            $table->dropColumn('inbox_at');
            $table->dropColumn('pack_by');
            $table->dropColumn('pack_at');
        });
    }
}
