<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSmtProductListTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::Table('smt_product_list', function (Blueprint $table) {
        $table->string('ownerMemberId')->comment('速卖通帐号');
        $table->string('ownerMemberSeq')->comment('用户系列号');
        $table->string('wsDisplay')->comment('商品下架原因：expire_offline：过期下架，user_offline：用户下架,violate_offline：违规下架,punish_offline：交易违规下架，degrade_offline：降级下架');
        $table->integer('quantitySold1')->comment('近30天销量');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('smt_product_list', function (Blueprint $table) {
            $table->dropColumn('ownerMemberId');
            $table->dropColumn('ownerMemberSeq');
            $table->dropColumn('wsDisplay');
            $table->dropColumn('quantitySold1');
        });
    }
}
