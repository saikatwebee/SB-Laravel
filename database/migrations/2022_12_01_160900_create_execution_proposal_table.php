<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExecutionProposalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('execution_proposal', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('problem_id')->nullable()->index('execution_proposal_ibfk_1')->comment('link with problem table');
            $table->string('sb_proposal', 20)->nullable()->comment('Proposal amount of SolutionBuggy');
            $table->integer('expert_cid')->nullable()->index('execution_proposal_ibfk_3')->comment('link with customer table');
            $table->string('expert_proposal', 20)->nullable()->comment('Proposal amount of Consultant');
            $table->string('expert_gst', 20)->nullable();
            $table->integer('user_id')->nullable()->index('execution_proposal_ibfk_2')->comment('link with user table');
            $table->string('industry_file', 600)->nullable();
            $table->dateTime('date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('execution_proposal');
    }
}
