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
        Schema::table('database_complete', function (Blueprint $table) {
            $table->foreign(['assigned_to'], 'database_complete_ibfk_2')->references(['user_id'])->on('user')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign(['previously_assigned'], 'database_complete_ibfk_4')->references(['user_id'])->on('user')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign(['customer_id'], 'database_complete_ibfk_1')->references(['customer_id'])->on('customer')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign(['created_by'], 'database_complete_ibfk_3')->references(['user_id'])->on('user')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('database_complete', function (Blueprint $table) {
            $table->dropForeign('database_complete_ibfk_2');
            $table->dropForeign('database_complete_ibfk_4');
            $table->dropForeign('database_complete_ibfk_1');
            $table->dropForeign('database_complete_ibfk_3');
        });
    }
};
