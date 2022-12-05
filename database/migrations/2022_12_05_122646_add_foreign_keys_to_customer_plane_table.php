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
        Schema::table('customer_plane', function (Blueprint $table) {
            $table->foreign(['customer_id'], 'customer_plane_ibfk_2')->references(['customer_id'])->on('customer')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign(['subscriberplane_id'], 'customer_plane_ibfk_1')->references(['id'])->on('subscriberplane')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customer_plane', function (Blueprint $table) {
            $table->dropForeign('customer_plane_ibfk_2');
            $table->dropForeign('customer_plane_ibfk_1');
        });
    }
};
