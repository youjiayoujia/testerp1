<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaypalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('paypals', function (Blueprint $table) {
            $table->increments('id');
            $table->string('paypal_email_address')->comment('paypay')->default(NULL);
            $table->string('paypal_account')->comment('API paypal_account')->default(NULL);
            $table->string('paypal_password')->comment('API paypal_password')->comment(NULL);
            $table->text('paypal_token')->comment('paypal_token')->comment(NULL);
            $table->enum('is_enable', ['1', '2'])->comment('1 启用 2 弃用')->default('1');
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
        Schema::drop('paypals');
    }
}
