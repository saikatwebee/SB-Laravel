<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCrmActivityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('crm_activity', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('user_id')->nullable()->index('crm_activity_ibfk_1')->comment('link with user table');
            $table->integer('cus_id')->nullable()->index('crm_activity_ibfk_2')->comment('link with database_complete table');
            $table->integer('activity')->nullable()->comment('1- call,
2- note,
3- task,
4- customer interest,
5- grade,
6- industry,
7- skill/category,
8- Lead Questionire,
9- whatsapp template message');
            $table->integer('type')->nullable();
            $table->integer('call_outcome')->nullable();
            $table->mediumText('comments')->nullable();
            $table->dateTime('date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('crm_activity');
    }
}
