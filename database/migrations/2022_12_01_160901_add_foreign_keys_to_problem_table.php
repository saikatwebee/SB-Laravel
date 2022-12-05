<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToProblemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('problem', function (Blueprint $table) {
            $table->foreign(['assigned_to_1'], 'problem_ibfk_8')->references(['user_id'])->on('user')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign(['customer_id'], 'problem_ibfk_1')->references(['customer_id'])->on('customer')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign(['industries'], 'problem_ibfk_5')->references(['id'])->on('industries')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign(['assigned_to'], 'problem_ibfk_7')->references(['user_id'])->on('user')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign(['sub_cat'], 'problem_ibfk_3')->references(['id'])->on('category')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign(['skills'], 'problem_ibfk_6')->references(['id'])->on('skill')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('problem', function (Blueprint $table) {
            $table->dropForeign('problem_ibfk_8');
            $table->dropForeign('problem_ibfk_1');
            $table->dropForeign('problem_ibfk_5');
            $table->dropForeign('problem_ibfk_7');
            $table->dropForeign('problem_ibfk_3');
            $table->dropForeign('problem_ibfk_6');
        });
    }
}
