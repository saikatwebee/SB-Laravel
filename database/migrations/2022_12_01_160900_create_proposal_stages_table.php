<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProposalStagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('proposal_stages', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('pid')->nullable()->index('proposal_stages_ibfk_1')->comment('link with problem table');
            $table->string('industry', 650)->nullable();
            $table->string('consultant', 650)->nullable();
            $table->date('date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('proposal_stages');
    }
}
