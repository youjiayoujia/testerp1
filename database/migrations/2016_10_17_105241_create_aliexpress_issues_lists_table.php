<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAliexpressIssuesListsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('aliexpress_issues_lists', function(Blueprint $table) {
            $table->increments('id');
			$table->string('issue_id')->nullable();
			$table->string('gmt_modified')->nullable();
			$table->string('gmt_create')->nullable();
			$table->string('reason_cn')->nullable();
			$table->string('reason_en')->nullable();
			$table->string('channel_order_id')->nullable();
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
        Schema::drop('aliexpress_issues_lists');
    }

}
