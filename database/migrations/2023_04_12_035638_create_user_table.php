<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user', function (Blueprint $table) {
            $table->integer('user_id', true);
            $table->string('user_type', 3)->default('U');
            $table->integer('parent_id')->nullable();
            $table->string('firstname', 32)->default('');
            $table->string('lastname')->nullable()->default('');
            $table->string('email', 96)->default('')->unique('email');
            $table->string('languages', 2000)->nullable();
            $table->string('sales_target', 10)->nullable();
            $table->string('coupon', 6)->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('ip', 15)->default('');
            $table->boolean('status')->nullable();
            $table->dateTime('date_added')->nullable();
            $table->string('email_user', 100)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user');
    }
};
