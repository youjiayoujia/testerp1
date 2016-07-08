<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLogisticsRuleColumnWeather extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('logistics_rules', function (Blueprint $table) {
            $table->enum('weight_section',['0', '1'])->comment("重量区间")->default('0');
            $table->enum('order_amount_section', ['0', '1'])->comment('金额区间')->default('0');
            $table->enum('channel_section', ['0', '1'])->comment('渠道区间')->default('0');
            $table->enum('catalog_section', ['0', '1'])->comment('品类区间')->default('0');
            $table->enum('country_section', ['0', '1'])->comment('国家区间')->default('0');
            $table->enum('limit_section', ['0', '1'])->comment('物流限制区间')->default('0');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('logistics_rules', function (Blueprint $table) {
            $table->dropColumn('weight_section');
            $table->dropColumn('order_amount_section');
            $table->dropColumn('channel_section');
            $table->dropColumn('catalog_section');
            $table->dropColumn('country_section');
            $table->dropColumn('limit_section');
        });
    }
}
