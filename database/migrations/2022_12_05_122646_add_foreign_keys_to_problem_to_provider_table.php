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
        Schema::table('problem_to_provider', function (Blueprint $table) {
            $table->foreign(['problem_id'], 'problem_to_provider_ibfk_2')->references(['id'])->on('problem')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign(['customer_id'], 'problem_to_provider_ibfk_1')->references(['customer_id'])->on('customer')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('problem_to_provider', function (Blueprint $table) {
            $table->dropForeign('problem_to_provider_ibfk_2');
            $table->dropForeign('problem_to_provider_ibfk_1');
        });
    }
};
