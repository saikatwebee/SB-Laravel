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
        Schema::table('crm_activity', function (Blueprint $table) {
            $table->foreign(['user_id'], 'crm_activity_ibfk_3')->references(['user_id'])->on('user')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign(['cus_id'], 'crm_activity_ibfk_2')->references(['id'])->on('database_complete')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('crm_activity', function (Blueprint $table) {
            $table->dropForeign('crm_activity_ibfk_3');
            $table->dropForeign('crm_activity_ibfk_2');
        });
    }
};
