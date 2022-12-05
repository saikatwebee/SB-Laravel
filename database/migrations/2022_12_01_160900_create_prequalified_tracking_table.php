<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrequalifiedTrackingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prequalified_tracking', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->string('Register_high', 150);
            $table->string('Register_medium', 150);
            $table->string('Google_ads', 150);
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
        Schema::dropIfExists('prequalified_tracking');
    }
}
