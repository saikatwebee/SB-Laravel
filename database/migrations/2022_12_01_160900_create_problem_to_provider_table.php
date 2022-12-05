<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProblemToProviderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('problem_to_provider', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('customer_id')->nullable()->index('problem_to_provider_ibfk_1')->comment('link with customer table');
            $table->integer('problem_id')->nullable()->index('problem_to_provider_ibfk_2')->comment('link with problem table');
            $table->integer('action')->nullable()->default(0);
            $table->dateTime('date_added')->nullable();
            $table->string('offer', 400)->nullable();
            $table->integer('shortlist')->default(0);
            $table->integer('exe_doc')->default(0);
            $table->string('comment', 2000)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('problem_to_provider');
    }
}
