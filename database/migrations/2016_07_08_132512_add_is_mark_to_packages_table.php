<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIsMarkToPackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('packages', function (Blueprint $table) {
            $table->enum('is_mark',['0', '1'])->comment("0 未标记  1已标记")->default('0')->after('tracking_link');
            $table->enum('is_upload', ['0', '1','2'])->comment('0 未上传追踪号  1已上传追踪号 2 不需要上传追踪号')->default('0')->after('is_mark');

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
        Schema::table('packages', function (Blueprint $table) {
            $table->dropColumn('is_mark');
            $table->dropColumn('is_upload');
        });
    }
}
