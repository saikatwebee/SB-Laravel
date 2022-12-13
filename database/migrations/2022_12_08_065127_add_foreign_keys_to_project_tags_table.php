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
        Schema::table('project_tags', function (Blueprint $table) {
            $table->foreign(['problem_id'], 'project_tags_ibfk_1')->references(['id'])->on('problem')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('project_tags', function (Blueprint $table) {
            $table->dropForeign('project_tags_ibfk_1');
        });
    }
};
