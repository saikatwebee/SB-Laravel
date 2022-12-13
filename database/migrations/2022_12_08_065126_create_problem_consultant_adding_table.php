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
        Schema::create('problem_consultant_adding', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('name', 200)->nullable();
            $table->string('email', 200)->nullable();
            $table->string('phone', 20)->nullable();
            $table->integer('project_id')->nullable()->index('problem_consultant_adding_ibfk_1')->comment('link with problem table');
            $table->string('comments', 500)->nullable();
            $table->integer('user_id')->nullable()->index('problem_consultant_adding_ibfk_2')->comment('link with user table');
            $table->string('source', 300)->nullable();
            $table->timestamp('date_added')->useCurrentOnUpdate()->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('problem_consultant_adding');
    }
};
