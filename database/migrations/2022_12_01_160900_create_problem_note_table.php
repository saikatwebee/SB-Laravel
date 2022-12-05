<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProblemNoteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('problem_note', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('problem_id')->nullable()->index('problem_note_ibfk_2')->comment('link with problem table');
            $table->integer('activity')->nullable()->comment('1- call,
2- note,
3- task,
4- payment
notification,
5- Project stage');
            $table->integer('type')->nullable();
            $table->integer('call_outcome')->nullable();
            $table->integer('stage')->nullable();
            $table->date('end_date')->nullable();
            $table->integer('user_id')->nullable()->index('problem_note_ibfk_1')->comment('link with user table');
            $table->string('comments', 2000)->nullable();
            $table->string('meeting_link', 250)->nullable();
            $table->string('meeting_id', 250)->nullable();
            $table->string('meeting_room', 250)->nullable();
            $table->dateTime('date')->nullable();
            $table->string('stage_desc', 2000)->nullable();
            $table->integer('stage_duration')->nullable();
            $table->integer('gantt_stage')->nullable();
            $table->integer('stage_amount')->nullable();
            $table->integer('stage_status')->nullable()->default(0);
            $table->date('completion_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('problem_note');
    }
}
