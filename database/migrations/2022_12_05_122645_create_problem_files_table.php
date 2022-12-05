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
        Schema::create('problem_files', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('pid')->index('problem_files_ibfk_2')->comment('link with problem table');
            $table->integer('cid')->nullable()->index('problem_files_ibfk_1')->comment('link with customer table');
            $table->integer('ftype')->nullable()->comment('1-proposal files
2-industry proposal
3-payment files
4-advance payment
5-multiple doc file');
            $table->string('fpath')->nullable();
            $table->string('fname')->nullable();
            $table->dateTime('date_created')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('problem_files');
    }
};
