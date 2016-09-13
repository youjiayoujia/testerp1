<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePicklists extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('picklists', function (Blueprint $table) {
            $table->increments('id');
            $table->string('picknum')->comment('拣货单号')->default(NULL);
            $table->enum('type', ['SINGLE', 'SINGLEMULTI', 'MULTI'])->comment('拣货单类型')->default('SINGLE');
            $table->enum('status', ['NONE', 'PRINTED', 'PICKING', 'PICKED', 'INBOXING', 'INBOXED', 'PACKAGEING', 'PACKAGED'])->comment('拣货单状态')->default('NONE');
            $table->integer('logistic_id')->comment('物流号')->default(0);
            $table->integer('pick_by')->comment('拣货人')->default(0);
            $table->timestamp('print_at')->comment('打印时间');
            $table->timestamp('pick_at')->comment('拣货时间');
            $table->integer('inbox_by')->comment('分拣人');
            $table->timestamp('inbox_at')->comment('分拣时间');
            $table->integer('pack_by')->comment('包装人');
            $table->timestamp('pack_at')->comment('包装时间');
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
        Schema::drop('picklists');
    }
}
