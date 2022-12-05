<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerSkillTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_skill', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('customer_id')->nullable()->index('customer_skill_ibfk_1')->comment('link with customer table');
            $table->integer('skill_id')->nullable()->index('customer_skill_ibfk_2')->comment('link with skill table');
            $table->string('other')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customer_skill');
    }
}
