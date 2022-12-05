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
        Schema::table('payment_link', function (Blueprint $table) {
            $table->foreign(['plan_id'], 'payment_link_ibfk_2')->references(['id'])->on('subscriberplane')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign(['customer_id'], 'payment_link_ibfk_1')->references(['customer_id'])->on('customer')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payment_link', function (Blueprint $table) {
            $table->dropForeign('payment_link_ibfk_2');
            $table->dropForeign('payment_link_ibfk_1');
        });
    }
};
