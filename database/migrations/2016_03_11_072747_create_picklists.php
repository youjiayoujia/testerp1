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
            $table->string('picklist_id')->comment('拣货单号')->default(NULL);
            $table->enum('type', ['SINGLE', 'SINGLEMULTI', 'MULTI'])->comment('拣货单类型')->default('SINGLE');
            $table->enum('status', ['NONE', 'PICKING', 'INBOXING', 'INBOXED', 'PACKAGEING', 'PACKAGED'])->comment('拣货单状态')->default('NONE');
            $table->integer('logistic_id')->comment('物流号')->default(0);
            $table->integer('pick_by')->comment('拣货人')->default(0);
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
