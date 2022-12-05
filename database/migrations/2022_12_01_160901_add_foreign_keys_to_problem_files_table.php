<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToProblemFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('problem_files', function (Blueprint $table) {
            $table->foreign(['cid'], 'problem_files_ibfk_1')->references(['customer_id'])->on('customer')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign(['pid'], 'problem_files_ibfk_2')->references(['id'])->on('problem')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('problem_files', function (Blueprint $table) {
            $table->dropForeign('problem_files_ibfk_1');
            $table->dropForeign('problem_files_ibfk_2');
        });
    }
}
