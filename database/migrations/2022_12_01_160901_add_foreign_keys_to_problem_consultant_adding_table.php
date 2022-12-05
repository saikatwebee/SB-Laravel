<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToProblemConsultantAddingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('problem_consultant_adding', function (Blueprint $table) {
            $table->foreign(['user_id'], 'problem_consultant_adding_ibfk_2')->references(['user_id'])->on('user')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign(['project_id'], 'problem_consultant_adding_ibfk_3')->references(['id'])->on('problem')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('problem_consultant_adding', function (Blueprint $table) {
            $table->dropForeign('problem_consultant_adding_ibfk_2');
            $table->dropForeign('problem_consultant_adding_ibfk_3');
        });
    }
}
