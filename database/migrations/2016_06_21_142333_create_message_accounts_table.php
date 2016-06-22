<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMessageAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('message_accounts',function (Blueprint $table){
            $table->increments('id');
            $table->string('account')->nullable()->default(NULL);
            $table->string('name')->nullable()->default(NULL);
            $table->text('secret')->nullable()->default(NULL);
            $table->text('token')->nullable()->default(NULL);
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
        //
    }
}
