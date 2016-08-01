<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLazadaPackageIdToPackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //

        Schema::table('packages', function (Blueprint $table) {
            $table->string('lazada_package_id')->comment('lazada的PackageId')->default('');
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
            $table->dropColumn('lazada_package_id');
        });
    }
}
