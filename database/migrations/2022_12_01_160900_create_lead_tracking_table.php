<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeadTrackingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lead_tracking', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->string('Register_high', 150)->nullable();
            $table->string('Register_medium', 150)->nullable();
            $table->string('Google_ads', 150)->nullable();
            $table->string('Indiamart', 150)->nullable();
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
        Schema::dropIfExists('lead_tracking');
    }
}
