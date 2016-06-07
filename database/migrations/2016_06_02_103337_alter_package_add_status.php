<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterPackageAddStatus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('packages', function (Blueprint $table) {
            $table->enum('status',
                [
                    'NEW',
                    'ASSIGNED',
                    'ASSIGNFAILED',
                    'PROCESSING',
                    'PICKING',
                    'PICKED',
                    'PRINTED',
                    'PACKED',
                    'SHIPPED',
                    'ERROR',
                    'CANCLE',
                ])->default('NEW')->comment('状态');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('packages', function (Blueprint $table) {
            //
        });
    }
}
