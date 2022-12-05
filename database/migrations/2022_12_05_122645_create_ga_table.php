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
        Schema::create('ga', function (Blueprint $table) {
            $table->integer('cid')->index('cid')->comment('link with customer table');
            $table->string('query', 1000)->nullable();
            $table->string('channel', 500)->nullable();
            $table->string('campaign', 500)->nullable();
            $table->string('sourcemedium', 500)->nullable();
            $table->string('keyword', 500)->nullable();
            $table->string('destinationurl', 1500)->nullable();
            $table->string('device', 500)->nullable();
            $table->string('state', 500)->nullable();
            $table->string('city', 500)->nullable();
            $table->string('landingpage', 500)->nullable();
            $table->timestamp('date_added')->useCurrentOnUpdate()->useCurrent();

            $table->unique(['cid'], 'cid_2');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ga');
    }
};
