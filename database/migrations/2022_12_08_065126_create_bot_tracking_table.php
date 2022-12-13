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
        Schema::create('bot_tracking', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('name', 155)->nullable();
            $table->string('phone', 150)->nullable();
            $table->string('email', 155)->nullable();
            $table->integer('customer_id')->nullable()->index('bot_tracking_ibfk_1')->comment('link with customer table ');
            $table->string('comments', 1000)->nullable();
            $table->string('bot_name', 150)->nullable();
            $table->timestamp('creted')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bot_tracking');
    }
};
