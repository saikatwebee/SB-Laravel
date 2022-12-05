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
        Schema::table('problem_note', function (Blueprint $table) {
            $table->foreign(['problem_id'], 'problem_note_ibfk_4')->references(['id'])->on('problem')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign(['user_id'], 'problem_note_ibfk_3')->references(['user_id'])->on('user')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('problem_note', function (Blueprint $table) {
            $table->dropForeign('problem_note_ibfk_4');
            $table->dropForeign('problem_note_ibfk_3');
        });
    }
};
