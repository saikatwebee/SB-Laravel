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
        Schema::table('execution_forward', function (Blueprint $table) {
            $table->foreign(['problem_id'], 'execution_forward_ibfk_3')->references(['id'])->on('problem')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign(['customer_id'], 'execution_forward_ibfk_2')->references(['customer_id'])->on('customer')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('execution_forward', function (Blueprint $table) {
            $table->dropForeign('execution_forward_ibfk_3');
            $table->dropForeign('execution_forward_ibfk_2');
        });
    }
};
