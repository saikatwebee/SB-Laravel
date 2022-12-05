<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToProblemToProviderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('problem_to_provider', function (Blueprint $table) {
            $table->foreign(['customer_id'], 'problem_to_provider_ibfk_1')->references(['customer_id'])->on('customer')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign(['problem_id'], 'problem_to_provider_ibfk_2')->references(['id'])->on('problem')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('problem_to_provider', function (Blueprint $table) {
            $table->dropForeign('problem_to_provider_ibfk_1');
            $table->dropForeign('problem_to_provider_ibfk_2');
        });
    }
}
