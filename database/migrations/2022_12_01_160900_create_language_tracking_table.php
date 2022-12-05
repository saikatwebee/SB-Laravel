<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLanguageTrackingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('language_tracking', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->integer('Hindi')->nullable();
            $table->integer('Kannada')->nullable();
            $table->integer('Tamil')->nullable();
            $table->integer('Telugu')->nullable();
            $table->integer('Malayalam')->nullable();
            $table->timestamp('created')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('language_tracking');
    }
}
