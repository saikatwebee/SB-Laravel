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
        Schema::table('customer_skill', function (Blueprint $table) {
            $table->foreign(['skill_id'], 'customer_skill_ibfk_2')->references(['id'])->on('skill')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign(['customer_id'], 'customer_skill_ibfk_1')->references(['customer_id'])->on('customer')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customer_skill', function (Blueprint $table) {
            $table->dropForeign('customer_skill_ibfk_2');
            $table->dropForeign('customer_skill_ibfk_1');
        });
    }
};
