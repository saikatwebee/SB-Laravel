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
        Schema::create('auth', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('role', 3)->nullable();
            $table->string('name')->default('');
            $table->string('email', 96)->default('')->unique('email');
            $table->string('password')->default('');
            $table->string('otp', 450)->nullable();
            $table->string('code', 450)->nullable();
            $table->integer('Status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('auth');
    }
};
