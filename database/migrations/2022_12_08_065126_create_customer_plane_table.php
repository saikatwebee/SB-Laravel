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
        Schema::create('customer_plane', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('customer_id')->nullable()->unique('customer_id')->comment('link with customer table');
            $table->integer('subscriberplane_id')->nullable()->index('customer_plane_ibfk_1')->comment('link with subscriberplane table');
            $table->date('exp_plane')->nullable()->comment('customer plan expiry Date');
            $table->integer('problem')->nullable()->comment('post credit for Industry');
            $table->integer('apply')->nullable()->comment('apply credit for Consultant');
            $table->dateTime('date_updated')->nullable();

            $table->index(['customer_id'], 'customer_plane_ibfk_2');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customer_plane');
    }
};
