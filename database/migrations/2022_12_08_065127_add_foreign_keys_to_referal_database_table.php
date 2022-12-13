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
        Schema::table('referal_database', function (Blueprint $table) {
            $table->foreign(['referer_id'], 'referal_database_ibfk_2')->references(['customer_id'])->on('customer')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign(['customer_id'], 'referal_database_ibfk_1')->references(['customer_id'])->on('customer')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('referal_database', function (Blueprint $table) {
            $table->dropForeign('referal_database_ibfk_2');
            $table->dropForeign('referal_database_ibfk_1');
        });
    }
};
