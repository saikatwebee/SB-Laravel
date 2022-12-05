<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToExecutionPaymentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('execution_payment', function (Blueprint $table) {
            $table->foreign(['invoice_id'], 'execution_payment_ibfk_1')->references(['id'])->on('invoice')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign(['problem_id'], 'execution_payment_ibfk_2')->references(['id'])->on('problem')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('execution_payment', function (Blueprint $table) {
            $table->dropForeign('execution_payment_ibfk_1');
            $table->dropForeign('execution_payment_ibfk_2');
        });
    }
}
