<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLogisticsEmailTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('logistics_email_templates', function (Blueprint $table) {
            $table->string('country_code')->comment('国家代码')->nullable()->default(NULL)->after('sender');
            $table->string('province')->comment('省份')->nullable()->default(NULL)->after('sender');
            $table->string('city')->comment('城市')->nullable()->default(NULL)->after('sender');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('logistics_email_templates', function (Blueprint $table) {
            $table->dropColumn('country_code');
            $table->dropColumn('province');
            $table->dropColumn('city');
        });
    }
}
