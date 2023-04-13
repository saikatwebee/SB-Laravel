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
        Schema::create('consultant_req', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('cid')->index('cid');
            $table->string('freelance', 450)->nullable();
            $table->string('location')->nullable();
            $table->string('state')->nullable();
            $table->string('duration')->nullable();
            $table->string('organizations')->nullable();
            $table->string('relocate')->nullable();
            $table->string('startProject')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('consultant_req');
    }
};
