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
        Schema::create('offline_tracking', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->string('email', 150);
            $table->string('call_ph', 150);
            $table->string('whatsapp', 150);
            $table->string('management', 150);
            $table->string('others', 150);
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
        Schema::dropIfExists('offline_tracking');
    }
};
