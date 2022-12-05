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
        Schema::table('execution_proposal', function (Blueprint $table) {
            $table->foreign(['user_id'], 'execution_proposal_ibfk_4')->references(['user_id'])->on('user')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign(['expert_cid'], 'execution_proposal_ibfk_3')->references(['customer_id'])->on('customer')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign(['problem_id'], 'execution_proposal_ibfk_5')->references(['id'])->on('problem')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('execution_proposal', function (Blueprint $table) {
            $table->dropForeign('execution_proposal_ibfk_4');
            $table->dropForeign('execution_proposal_ibfk_3');
            $table->dropForeign('execution_proposal_ibfk_5');
        });
    }
};
