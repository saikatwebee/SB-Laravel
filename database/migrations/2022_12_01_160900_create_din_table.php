<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDinTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('din', function (Blueprint $table) {
            $table->integer('din', true);
            $table->string('name', 500);
            $table->string('mobile', 100);
            $table->string('email', 500);
            $table->string('mobile1', 100);
            $table->string('email1', 500);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('din');
    }
}
