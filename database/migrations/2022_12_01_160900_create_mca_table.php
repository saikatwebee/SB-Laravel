<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMcaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mca', function (Blueprint $table) {
            $table->string('cin', 100)->unique('cin_2');
            $table->string('company', 500);
            $table->date('date_reg');
            $table->string('state', 100);
            $table->integer('activity_code');
            $table->string('activity', 500);
            $table->string('address', 500);
            $table->string('email', 500);

            $table->index(['cin'], 'cin');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mca');
    }
}
