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
        Schema::create('player_notification', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->text('player_id')->nullable();
            $table->text('device_name')->nullable();
            $table->integer('customer_id')->nullable()->index('player_notification_ibfk_1')->comment('link with customer table');
            $table->timestamp('date_added')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('player_notification');
    }
};
